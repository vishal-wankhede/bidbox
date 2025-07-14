<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\ReportLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyImpressionLog extends Command
{
  protected $signature = 'report:daily-log';
  protected $description = 'Log daily impression updates per campaign by gender, location, filters';

  public function handle()
  {
    $now = Carbon::now();
    $targetDay = $now->copy()->subDay(); // One hour before now
    $targetDate = $targetDay->toDateString(); // 'YYYY-MM-DD'

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

      $dayImpressions = $totalImpressions * ($dayPercent / 100);

      // Breakdown data
      $genders = $campaign->gender ?? [];
      $locations = $campaign->locations ?? [];
      $filters = $campaign->filtervalues ?? [];
      $divisions = $campaign->division_value ?? [];

      if (empty($genders)) {
        $genders = [0 => 0];
      } else {
        foreach ($genders as $key => $value) {
          $genders[$key] = ceil(($value * $dayImpressions) / 100);
        }
      }
      if (empty($locations)) {
        $locations = [0 => 0];
      } else {
        foreach ($locations as $key => $value) {
          $locations[$key] = ceil(($value * $dayImpressions) / 100);
        }
      }
      if (empty($filters)) {
        $filters = [0 => 0];
      } else {
        foreach ($filters as $filter => $filter_value) {
          foreach ($filter_value as $key => $value) {
            $filters[$filter][$key] = ceil(($value * $dayImpressions) / 100);
          }
        }
      }
      if (empty($divisions)) {
        $divisions = [0 => 0];
      } else {
        foreach ($divisions as $division => $division_value) {
          foreach ($division_value as $key => $value) {
            $divisions[$division][$key] = ceil(($value * $dayImpressions) / 100);
          }
        }
      }

      $clicks = round($dayImpressions * ($campaign->ctr / 100)) + 5;
      $videoViews = round($dayImpressions * ($campaign->vtr / 100)) + 5;

      $campaign->creatives = $campaign->creatives
        ->map(function ($creative) use ($dayImpressions) {
          return [
            $creative->id => round($dayImpressions * ($creative->percentage / 100)) + 6,
          ];
        })
        ->toArray();

      $report = ReportLog::create([
        'campaign_id' => $campaign->id,
        'report_type' => 'daily',
        'impressions' => $dayImpressions,
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

      $this->info("Logged {$dayImpressions} impressions for Campaign ID {$campaign->id} on {$targetDate}");
    }
  }
}
