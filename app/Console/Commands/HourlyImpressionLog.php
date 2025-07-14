<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\ReportLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HourlyImpressionLog extends Command
{
  protected $signature = 'report:hourly-log';
  protected $description = 'Log hourly impression updates per campaign by gender, location, filters';

  public function handle()
  {
    $now = Carbon::now();
    $targetHour = $now->copy()->subHour(); // One hour before now
    $targetDate = $targetHour->toDateString(); // 'YYYY-MM-DD'
    $hourIndex = $targetHour->hour; // 0 to 23

    $campaigns = Campaign::whereDate('start_date', '<=', $targetDate)
      ->whereDate('end_date', '>=', $targetDate)
      ->get();

    foreach ($campaigns as $campaign) {
      $projectionDetails = json_decode($campaign->projection_details, true);
      $totalImpressions = $campaign->impressions;

      if (!isset($projectionDetails[$targetDate])) {
        continue;
      }

      $dayData = $projectionDetails[$targetDate];
      $dayPercent = $dayData['percentage'] ?? 0;
      $hourPercent = $dayData['hourlyPercentages'][$hourIndex] ?? 0;

      $dayImpressions = $totalImpressions * ($dayPercent / 100);

      if (!isset($dayData['hourlyPercentages'][$hourIndex])) {
        continue;
      }

      // Total impressions to log for this hour
      $hourlyImpressions = round($dayImpressions * ($hourPercent / 100));

      // Breakdown data
      $genders = $campaign->gender ?? [];
      $locations = $campaign->locations ?? [];
      $filters = $campaign->filtervalues ?? [];
      $divisions = $campaign->division_value ?? [];

      if (empty($genders)) {
        $genders = [0 => 0];
      } else {
        foreach ($genders as $key => $value) {
          $genders[$key] = ceil(($value * $hourlyImpressions) / 100);
        }
      }
      if (empty($locations)) {
        $locations = [0 => 0];
      } else {
        foreach ($locations as $key => $value) {
          $locations[$key] = ceil(($value * $hourlyImpressions) / 100);
        }
      }
      if (empty($filters)) {
        $filters = [0 => 0];
      } else {
        foreach ($filters as $filter => $filter_value) {
          foreach ($filter_value as $key => $value) {
            $filters[$filter][$key] = ceil(($value * $hourlyImpressions) / 100);
          }
        }
      }
      if (empty($divisions)) {
        $divisions = [0 => 0];
      } else {
        foreach ($divisions as $division => $division_value) {
          foreach ($division_value as $key => $value) {
            $divisions[$division][$key] = ceil(($value * $hourlyImpressions) / 100);
          }
        }
      }

      $clicks = round($hourlyImpressions * ($campaign->ctr / 100)) + 5;
      $videoViews = round($hourlyImpressions * ($campaign->vtr / 100)) + 5;

      $campaign->creatives = $campaign->creatives
        ->map(function ($creative) use ($hourlyImpressions) {
          return [
            $creative->id => round($hourlyImpressions * ($creative->percentage / 100)) + 6,
          ];
        })
        ->toArray();

      $report = ReportLog::create([
        'campaign_id' => $campaign->id,
        'report_type' => 'hourly',
        'impressions' => $hourlyImpressions,
        'clicks' => $clicks,
        'video_views' => $videoViews,
        'locations' => $locations,
        'gender' => $genders,
        'filter_values' => $filters,
        'division_values' => $divisions,
        'creatives' => $campaign->creatives,
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      $this->info("Logged {$hourlyImpressions} impressions for Campaign ID {$campaign->id} at hour {$hourIndex}");
    }
  }
}
