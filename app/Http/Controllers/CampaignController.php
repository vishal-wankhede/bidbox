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
use App\Models\Master;
use App\Models\MasterLocationDetail;
use App\Models\Permission;
use Faker\Core\File;
use Faker\Provider\ar_EG\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yoeunes\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
  //
  public function index(Request $request)
  {
    $userId = Auth::id();

    $campaignIds = DB::table('campaign_user')
      ->where('user_id', $userId)
      ->pluck('campaign_id');

    $campaigns = Campaign::where('status', 'active')
      ->whereIn('id', $campaignIds)
      ->get();

    return view('content.pages.campaignlist', [
      'campaigns' => $campaigns,
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
    $data['masters'] = Master::all();

    return view('content.pages.addcampaign', $data);
  }

  public function store(Request $request)
  {
    $userId = Auth::id();

    // return Auth::id();
    $validator = Validator::make($request->all(), [
      'campaign_name' => 'required|string|max:255',
      'brand_name' => 'required|string|max:255',
      'channel' => 'required|string',
      'impressions' => 'required|integer|min:1',
      'ctr' => 'required_unless:channel,Connected TV Advertising|numeric|max:100|min:0',
      'budget_type' => 'required|string',
      'total_budget' => 'required|numeric|min:0',
      'vtr' => 'nullable|numeric|max:100|min:0',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'location_percentages' => 'required',
      'gender_percentages' => 'required',
      'brand_logo' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:2048',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      return $errors;
      foreach ($errors as $error) {
        Toastr::error($error, 'error');
      }
      return redirect()
        ->back()
        ->withInput();
    }

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
      'master_id' => $request->master,
      'client_view_name' => $request->client_view_name,
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
      'budget_type' => $request->budget_type,
      'budget' => $request->total_budget,
    ]);

    // ✅ Upload and store creative files
    if ($request->hasFile('creatives')) {
      foreach ($request->file('creatives') as $i => $file) {
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

    DB::table('campaign_user')->insert([
      'user_id' => $userId,
      'campaign_id' => (int) $campaign->id,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

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
      $campaign->status = 'archived';
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
    // Proceed with master data
    $MasterData = MasterLocationDetail::whereIn('location_id', $request->locations)->where(
      'master_id',
      $request->master
    );
    if ($request->has('divisions')) {
      foreach ($request->divisions as $divisionId => $divisionValues) {
        if ($divisionId == 12) {
          $MasterData = $MasterData->where('filter_id', $divisionId)->whereIn('filter_value_id', $divisionValues);
        } elseif ($divisionId == 3) {
          // cohorts
          $MasterData = $MasterData->where('filter_id', $divisionId)->whereIn('filter_value_id', $divisionValues);
        }
      }
    }
    if ($request->has('filters')) {
      foreach ($request->filters as $filterId => $filterValues) {
        if ($filterId == 2) {
          //device type
          $MasterData = $MasterData->where('filter_id', $filterId)->whereIn('filter_value_id', $filterValues);
        } elseif ($filterId == 1) {
          //age range
          $MasterData = $MasterData->where('filter_id', $filterId)->whereIn('filter_value_id', $filterValues);
        }
      }
    }

    $MasterData->get();

    $population = 0;
    if (in_array(1, $request->gender)) {
      $population += $MasterData->sum('male');
    }
    if (in_array(2, $request->gender)) {
      $population += $MasterData->sum('female');
    }
    if (in_array(3, $request->gender)) {
      $population += $MasterData->sum('other');
    }

    return response()->json([
      'population' => $population,
      'status' => 200,
    ]);
  }
  public function runQuery(Request $request)
  {
    $query = $request->input('query');

    if (!$query) {
      return response()->json(['error' => 'Query is required'], 400);
    }

    try {
      // You can use select for SELECT queries
      if (strtolower(substr(trim($query), 0, 6)) === 'select') {
        $result = DB::select(DB::raw($query));
        return response()->json(['result' => $result]);
      }

      // Use statement for INSERT/UPDATE/DELETE
      $affected = DB::statement($query);
      return response()->json(['success' => true, 'affected_rows' => $affected]);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
