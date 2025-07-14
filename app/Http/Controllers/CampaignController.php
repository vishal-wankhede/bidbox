<?php

namespace App\Http\Controllers;

use App\Models\AgeRange;
use App\Models\Campaign;
use App\Models\CampaignCreative;
use App\Models\Country;
use App\Models\Filter;
use App\Models\FilterValue;
use App\Models\Gender;
use App\Models\Location;
use App\Models\LocationDetail;
use App\Models\Permission;
use Faker\Core\File;
use Faker\Provider\ar_EG\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yoeunes\Toastr\Facades\Toastr;

class CampaignController extends Controller
{
  //
  public function index(Request $request)
  {
    // Logic to retrieve and display campaigns
    return view('content.pages.campaignlist', [
      'campaigns' => Campaign::where('status', 'active')->get(),
    ]);
  }

  public function getarchive(Request $request)
  {
    return view('content.pages.archivedcampaign', [
      'campaigns' => Campaign::where('status', 'archive')->get(),
    ]);
  }

  public function add(Request $request)
  {
    $data['locations'] = Location::where('parent', null)->get();
    $data['filters'] = Filter::where('parent_id', null)
      ->where('child', 0)
      ->orderBy('filter_order')
      ->get();
    $data['divisions'] = Filter::where('parent_id', null)
      ->where('child', 1)
      ->get();
    return view('content.pages.addcampaign', $data);
  }

  public function store(Request $request)
  {
    // return $request->all();
    $validated = $request->validate([
      'campaign_name' => 'required|string|max:255',
      'brand_name' => 'required|string|max:255',
      'channel' => 'required|string',
      'impressions' => 'required|integer|min:1',
      'ctr' => 'nullable|numeric',
      'vtr' => 'nullable|numeric',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'brand_logo' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:2048',
      'creative_files.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4|max:10000',
    ]);

    // ✅ Upload brand logo
    $logoPath = null;
    if ($request->hasFile('brand_logo')) {
      $file = $request->file('brand_logo');
      $filename =
        time() .
        '-' .
        Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) .
        '.' .
        $file->getClientOriginalExtension();
      $file->move(public_path('uploads/brand_logos'), $filename);
      $logoPath = 'uploads/brand_logos/' . $filename;
    }

    // ✅ Create campaign
    $campaign = Campaign::create([
      'campaign_name' => $request->campaign_name,
      'campaign_description' => $request->campaign_description,
      'brand_name' => $request->brand_name,
      'brand_logo' => $logoPath,
      'channel' => $request->channel,
      'impressions' => $request->impressions,
      'ctr' => $request->ctr,
      'vtr' => $request->vtr,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date,
      'projection_details' => $request->projection_details,
      'locations' => $request->location_percentages ?? [],
      'gender' => $request->gender_percentages ?? [],
      'filtervalues' => $request->filter_percentages ?? [],
      'division_value' => $request->division_percentages ?? [],
    ]);

    // ✅ Upload and store creative files
    if ($request->hasFile('creative_files')) {
      foreach ($request->file('creative_files') as $i => $file) {
        $filename =
          time() .
          '-' .
          Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) .
          '.' .
          $file->getClientOriginalExtension();
        $file->move(public_path('uploads/creatives'), $filename);

        $meta = $request->creatives[$i] ?? [];

        CampaignCreative::create([
          'campaign_id' => $campaign->id,
          'file_name' => $meta['name'] ?? $file->getClientOriginalName(),
          'file_path' => 'uploads/creatives/' . $filename,
          'file_type' => $meta['type'] ?? 'Unknown',
          'dimensions' => $meta['dimensions'] ?? null,
          'size' => $meta['size'] ?? null,
          'percentage' => $meta['percentage'] ?? 0,
        ]);
      }
    }

    return redirect()
      ->back()
      ->with('success', 'Campaign & creatives saved successfully.');
  }

  public function archive($id)
  {
    $campaign = Campaign::find($id);
    if (!$campaign) {
      Toastr::error('Campaign not found', 'error');
      return redirect()->back();
    }
    if ($campaign->status == 'active') {
      $campaign->status = 'archive';
      $campaign->save();
      Toastr::success('Campaign archived successfully', 'success');
      return redirect()->back();
    } else {
      $campaign->status = 'active';
      $campaign->save();
      Toastr::success('Campaign unarchived successfully', 'success');
      return redirect()->back();
    }
    return redirect()->back();
  }

  public function destroy(Request $request, $id)
  {
    // Logic to delete a campaign
    // Find the campaign by ID and delete it
    return redirect()->back();
  }

  public function getTargetAudience(Request $request)
  {
    // return $request->all();
    $LocationDetails = LocationDetail::query();

    if (!empty($request->locations)) {
      // 1. Location Filter
      $requested = $request->locations ?? []; // array of selected location IDs
      $final = [];

      foreach ($requested as $locId) {
        $hasChildInRequest = Location::where('parent', $locId)
          ->whereIn('id', $requested)
          ->exists();

        if (!$hasChildInRequest) {
          $final[] = (int) $locId;
        }
      }

      $arr_final = array_values(array_unique($final)); // final array without parent if child exists

      $LocationDetails->where(function ($query) use ($arr_final) {
        foreach ($arr_final as $val) {
          $query->orWhereRaw('FIND_IN_SET(?, parent_locations)', [$val]);
        }
      });
    }
    // 2. Gender Filter
    if (!empty($request->gender)) {
      $LocationDetails->where(function ($query) use ($request) {
        foreach ($request->gender as $val) {
          $query->orWhereRaw('FIND_IN_SET(?, gender_id)', [$val]);
        }
      });
    }

    if (!empty($request->filters)) {
      $inputFilters = collect($request->filters);

      // Step 1: Extract and prepare filter_id → values mapping
      $filterValueMap = $inputFilters->mapWithKeys(function ($item) {
        return [(int) $item['filter_id'] => array_map('intval', $item['values'] ?? [])];
      });

      $filterIds = $filterValueMap->keys();

      // Step 2: Fetch filter metadata sorted by filter_order
      $filtersMeta = Filter::whereIn('id', $filterIds)
        ->orderBy('filter_order')
        ->get(['id', 'filter_order']);

      // Step 3: Rebuild the array in order (filter_id → values)
      $orderedFilters = $filtersMeta
        ->map(function ($filter) use ($filterValueMap) {
          return [
            'filter_id' => $filter->id,
            'values' => $filterValueMap[$filter->id] ?? [],
          ];
        })
        ->values();

      // Step 4: Merge all values in correct order
      $allValues = $orderedFilters
        ->pluck('values') // get collection of arrays
        ->flatten() // flatten to single array
        ->values(); // reindex

      // Step 5: Split into all-but-last and last
      $last_val = $allValues->pop(); // removes and returns last element
      $values = $allValues->toArray(); // remaining values

      $LocationDetails->where('filter_value_id', $last_val)->where(function ($query) use ($values) {
        foreach ($values as $val) {
          $query->orWhereRaw('FIND_IN_SET(?, parent_detail_id)', [$val]);
        }
      });
    }

    $population = $LocationDetails->sum('population_value');

    return response()->json([
      'population' => $population,
      'status' => 200,
    ]);
  }
}
