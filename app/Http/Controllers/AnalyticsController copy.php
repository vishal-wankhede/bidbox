<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignCreative;
use App\Models\Filter;
use App\Models\FilterValue;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\ReportLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class AnalyticsController extends Controller
{
  public function index(Request $request)
  {
    $data = [];

    // Campaign selected
    if ($request->campaign && $request->campaign !== 'Select Campaign') {
      $campaign = Campaign::find($request->campaign);
      $report = ReportLog::where('campaign_id', $request->campaign);

      // Filter by location if selected
      if ($request->location && $request->location !== 'Select Location') {
        $report = $report->where('locations', 'like', '%' . $request->location . '%');
      }

      // Filter by report type (hourly/daily)
      if ($request->type && $request->type !== 'Select Type') {
        $report = $report->where('report_type', $request->type);
      } else {
        $report = $report->where('report_type', 'daily');
      }

      $report = $report->orderBy('created_at', 'asc')->get();

      if ($report->isEmpty()) {
        return redirect()
          ->back()
          ->with('error', 'No report found for this campaign.');
      }
      $dates = $report
        ->pluck('created_at')
        ->map(function ($date) {
          return Carbon::parse($date)->format('d/m');
        })
        ->unique();
      $campaignctr = $report
        ->map(function ($item) {
          return $item->impressions > 0 ? round(($item->clicks / $item->impressions) * 100, 2) : 0;
        })
        ->toArray();

      $campaignvtr = $report
        ->map(function ($item) {
          return $item->impressions > 0 ? round(($item->video_views / $item->impressions) * 100, 2) : 0;
        })
        ->toArray();

      $ageRangeFilter = Filter::where('title', 'Age Range')->first();
      $deviceFilter = Filter::where('title', 'Device')->first();
      $filters = $report->pluck('filter_values')->toArray();
      $ageRangeArray = [];
      $deviceArray = [];
      foreach ($filters as $key => $filter) {
        if (isset($filter[$ageRangeFilter->id])) {
          foreach ($filter[$ageRangeFilter->id] as $ageKeyid => $ageValue) {
            $ageKey = FilterValue::where('id', $ageKeyid)->value('title');
            if (!$ageKey) {
              continue; // Skip if ageKey is not found
            }
            if (isset($ageRangeArray[$ageKey])) {
              $ageRangeArray[$ageKey] += $ageValue;
            } else {
              $ageRangeArray[$ageKey] = $ageValue;
            }
          }
        }

        if (isset($filter[$deviceFilter->id])) {
          foreach ($filter[$deviceFilter->id] as $deviceKeyid => $deviceValue) {
            $deviceKey = FilterValue::where('id', $deviceKeyid)->value('title');
            if (!$deviceKey) {
              continue; // Skip if deviceKey is not found
            }
            if (isset($deviceArray[$deviceKey])) {
              $deviceArray[$deviceKey] += $deviceValue;
            } else {
              $deviceArray[$deviceKey] = $deviceValue;
            }
          }
        }
      }

      $gender_array = [];
      $genders = $report->pluck('gender')->toArray();
      foreach ($genders as $gender) {
        foreach ($gender as $keyid => $value) {
          if ($keyid == 1) {
            $key = 'male';
          } elseif ($keyid == 2) {
            $key = 'female';
          } elseif ($keyid == 3) {
            $key = 'other';
          } else {
            $key = 'unknown';
          }
          if (isset($gender_array[$key])) {
            $gender_array[$key] += $value;
          } else {
            $gender_array[$key] = $value;
          }
        }
      }
      $totalGender = array_sum($gender_array);
      $genderRatios = [];

      foreach ($gender_array as $key => $value) {
        $genderRatios[$key] = $totalGender > 0 ? round(($value / $totalGender) * 100) : 0;
      }

      $locations = $report->pluck('locations')->toArray();

      $locationArray = [
        'countries' => [],
        'states' => [],
        'cities' => [],
      ];

      foreach ($locations as $locationSet) {
        foreach ($locationSet as $locationId => $value) {
          $location = Location::find($locationId);
          if (!$location) {
            continue;
          }

          $impressions = $value;

          // COUNTRY LEVEL (No parent_master)
          if ($location->parent_master == null) {
            if (!isset($locationArray['countries'][$location->name])) {
              $locationArray['countries'][$location->name] = 0;
            }
            $locationArray['countries'][$location->name] += $impressions;
          }

          // PARENT LEVELS EXIST
          else {
            $parents = explode(',', $location->parent_master);

            // STATE LEVEL (Only one parent)
            if (count($parents) == 1) {
              $parent = Location::find($parents[0]);
              if ($parent) {
                if (!isset($locationArray['states'][$parent->name])) {
                  $locationArray['states'][$parent->name] = 0;
                }
                $locationArray['states'][$parent->name] += $impressions;
              }
            }

            // CITY LEVEL (Multiple parents)
            elseif (count($parents) > 1) {
              foreach ($parents as $parentId) {
                $parent = Location::find($parentId);
                if ($parent) {
                  if (!isset($locationArray['cities'][$parent->name])) {
                    $locationArray['cities'][$parent->name] = 0;
                  }
                  $locationArray['cities'][$parent->name] += $impressions;
                }
              }
            }
          }
        }
      }

      // return $locationArray;

      $InventoryFilter = Filter::where('title', 'Inventories')->first();
      $CohortFilter = Filter::where('title', 'Cohorts')->first();
      $filters1 = $report->pluck('division_values')->toArray();
      $InventoryArray = [];
      $CohortArray = [];
      foreach ($filters1 as $key => $filter) {
        if (isset($filter[$InventoryFilter->id])) {
          foreach ($filter[$InventoryFilter->id] as $inventoryKeyid => $inventoryValue) {
            $inventoryKey = FilterValue::where('id', $inventoryKeyid)->value('title');
            if (!$inventoryKey) {
              continue; // Skip if inventoryKey is not found
            }
            if (isset($InventoryArray[$inventoryKey])) {
              $InventoryArray[$inventoryKey] += $inventoryValue;
            } else {
              $InventoryArray[$inventoryKey] = $inventoryValue;
            }
          }
        }
        foreach ($InventoryArray as $key => $value) {
          $percent = $value > 0 ? round(($value / $report->sum('impressions')) * 100, 2) : 0;
          $InventoryArray[$key]['ctr'] = round(($percent / 100) * (array_sum($campaignctr) / count($campaignctr)));
        }

        if (isset($filter[$CohortFilter->id])) {
          foreach ($filter[$CohortFilter->id] as $CohortKeyid => $CohortValue) {
            $CohortKey = FilterValue::where('id', $CohortKeyid)->value('title');
            if (!$CohortKey) {
              continue; // Skip if CohortKey is not found
            }
            if (isset($CohortArray[$CohortKey])) {
              $CohortArray[$CohortKey] += $CohortValue;
            } else {
              $CohortArray[$CohortKey] = $CohortValue;
            }
          }
        }
      }
      foreach ($CohortArray as $key => $value) {
        $percent = $value > 0 ? round(($value / $report->sum('impressions')) * 100, 2) : 0;
        $CohortArray[$key]['ctr'] = round(($percent / 100) * (array_sum($campaignctr) / count($campaignctr)));
      }

      $creatives = [];
      $creativesData = $report->pluck('creatives')->toArray();
      foreach ($creativesData as $creative) {
        foreach ($creative as $key => $value) {
          foreach ($value as $creativeId => $impressions) {
            $creativeName = CampaignCreative::where('id', $creativeId)->first();
            if (!$creativeName) {
              continue; // Skip if creativeName is not found
            }
            if (isset($creatives[$creativeName->id])) {
              $creatives[$creativeName->id] += $impressions;
            } else {
              $creatives[$creativeName->id] = [
                'id' => $creativeId,
                'impressions' => $impressions,
                'file_path' => $creativeName->file_path,
              ];
            }
          }
        }
      }
      foreach ($creatives as $key => $value) {
        $percent = $value > 0 ? round(($value / $report->sum('impressions')) * 100, 2) : 0;
        $creatives[$key]['ctr'] = round(($percent / 100) * (array_sum($campaignctr) / count($campaignctr)));
        $creatives[$key]['vtr'] = round(($percent / 100) * (array_sum($campaignvtr) / count($campaignvtr)));
      }

      // Build report summary
      $data['report'] = [
        'impressions' => $report->sum('impressions'),
        'clicks' => $report->sum('clicks'),
        'video_views' => $report->sum('video_views'),
        'ctr_arr' => $campaignctr,
        'ctr' => round(array_sum($campaignctr) / count($campaignctr)),
        'vtr_arr' => $campaignvtr,
        'vtr' => round(array_sum($campaignvtr) / count($campaignvtr)),
        'impression_daily' => json_encode($report->pluck('impressions')->toArray()),
        'clicks_daily' => json_encode($report->pluck('clicks')->toArray()),
        'video_views_daily' => json_encode($report->pluck('video_views')->toArray()),
        'dateLabels' => json_encode($dates->toArray()),
        'dateLabels_arr' => $dates->toArray(),
        'ageRange' => $ageRangeArray,
        'device' => $deviceArray,
        'gender' => $genderRatios,
        'locations' => $locationArray,
        'inventories' => $InventoryArray,
        'cohorts' => $CohortArray,
        'creatives' => $creatives,
      ];
    } else {
      // Default report if no campaign selected
      $data['report'] = [
        'impressions' => 0,
        'clicks' => 0,
        'video_views' => 0,
        'ctr' => 0,
        'ctr_arr' => [0],
        'vtr' => 0,
        'vtr_arr' => [0],
        'impression_daily' => json_encode([0]),
        'clicks_daily' => json_encode([0]),
        'video_views_daily' => json_encode([0]),
        'dateLabels' => json_encode(['No Data']),
        'dateLabels_arr' => ['No Data'],
        'ageRange' => [],
        'device' => [],
        'gender' => [],
        'locations' => [],
        'inventories' => [],
        'cohorts' => [],
        'creatives' => [],
      ];
    }
    // return $data['report'];
    // Append additional data
    $data['campaigns'] = Campaign::where('status', 'active')->get();
    $divisions_name = Filter::where('parent_id', null)
      ->where('child', 1)
      ->get();
    $divisions_array = [];
    $FilterController = new FilterController();
    foreach ($divisions_name as $division) {
      $children = $FilterController->getDivisionChildren($division->id)->getData('true');

      $divisions_array[] = [
        'label' => $division->title,
        'values' => $children['values'] ?? [],
      ];
    }

    $data['divisions'] = $divisions_array ?? [];
    $data['countries'] = Location::where('parent_master', null)->get();
    $data['states'] = Location::where('parent_master', '!=', null)
      ->whereRaw('parent_master NOT LIKE \'%,%\'')
      ->get();
    $data['cities'] = Location::whereRaw('parent_master LIKE \'%,%\'')->get();
    $data['filters'] = Filter::where('parent_id', null)
      ->where('child', 0)
      ->with('filter_values')
      ->get();

    $data['selectedCampaign'] = Campaign::find($request->input('campaign')) ?? '';
    $data['selectedcountry'] = Location::find($request->input('country')) ?? '';
    $data['selectedcity'] = Location::find($request->input('city')) ?? '';
    $data['selectedstate'] = Location::find($request->input('state')) ?? '';
    $data['selectedGender'] = $request->gender ?? '';
    $data['selectedAgeRange'] = $request->age_range ?? '';
    $data['selectedDevice'] = $request->device ?? '';
    // return $data;
    return view('content.pages.analytics.index', $data);
  }

  public function testcron(Request $request)
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

      return response()->json([
        'data' => $report,
        'status' => 'success',
        'message' => 'Hourly report logged successfully.',
      ]);
    }
  }
}
