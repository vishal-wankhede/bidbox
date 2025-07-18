<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\FilterValue;
use App\Models\Location;
use App\Models\Master;
use App\Models\MasterData;
use App\Models\MasterLocationDetail;
use Dotenv\Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Yoeunes\Toastr\Facades\Toastr;

use function PHPSTORM_META\map;

class MasterController extends Controller
{
  //
  public function index()
  {
    return view('content.utilities.masters.index', [
      'masters' => Master::all(),
    ]);
  }

  public function store(Request $request)
  {
    $validator = FacadesValidator::make($request->all(), [
      'name' => 'required|string|max:255',
    ]);
    if ($validator->fails()) {
      Toastr::error('Validation error: ' . $validator->errors()->first(), 'Error');
      return redirect()
        ->back()
        ->withInput();
    }

    $master = Master::Create(['master_name' => $request->name, 'created_by' => auth()->user()->id ?? 1]);

    if ($master) {
      Toastr::success('Master created successfully', 'Success');
    } else {
      Toastr::error('Failed to create master', 'Error');
    }

    return redirect()->route('utilities.masters.index');
  }

  public function addDetails(Request $request, $master_id, $filter_id = null)
  {
    // Validate the ID
    if (!is_numeric($master_id) || $master_id <= 0) {
      Toastr::error('Invalid master ID provided', 'Error');
      return redirect()->route('utilities.masters.index');
    }
    if ($filter_id && !is_numeric($filter_id)) {
      Toastr::error('Invalid filter ID provided', 'Error');
      return redirect()->route('utilities.masters.index');
    } elseif ($filter_id == null) {
      $filter = Filter::where('filter_order', 1)
        ->with('filter_values')
        ->first();
    } else {
      $filter_parent = Filter::where('id', $filter_id)
        ->with('filter_values')
        ->first();
      if (!$filter_parent) {
        Toastr::error('Filter not found', 'Error');
        return redirect()->route('utilities.masters.index');
      }
      $order = $filter_parent->filter_order + 1;
      $filter = Filter::where('filter_order', $order)
        ->with('filter_values')
        ->first();
    }
    if ($filter->child == 1) {
      $childFilters = Filter::whereRaw('FIND_IN_SET(?, parent_master)', [$filter->id])->get();
      $childValues = [];
      foreach ($childFilters as $childFilter) {
        $values = FilterValue::where('filter_id', $childFilter->id)->get();
        $childValues = array_merge($childValues, $values->toArray());
      }
      foreach ($childValues as &$childValue) {
        $parent = Filter::find($childValue['filter_id']);
        $breadcrumb = $parent->title;
        while ($parent && $parent->parent_id != null) {
          $parent = Filter::find($parent->parent_id);
          $breadcrumb = $parent->title . ' -> ' . $breadcrumb;
        }
        $childValue['breadcrumb'] = $breadcrumb;
      }
      unset($childValue);
      // return $childValues;
      $filter->filterValues = collect($childValues); // Convert to collection for consistency
    }
    if (!$filter) {
      Toastr::error('Filter not found', 'Error');
      return redirect()->route('utilities.masters.index');
    }
    $saved_data = MasterData::where('master_id', $master_id)
      ->where('filter_id', $filter->id)
      ->get();
    if ($saved_data->isEmpty()) {
      $saved_data_arr = [];
    } else {
      $saved_data_arr = [];
      foreach ($saved_data as $item) {
        $saved_data_arr[$item->filter_value_id] = [
          'male' => $item->male,
          'female' => $item->female,
          'other' => $item->other,
        ];
      }
    }
    $max_order = Filter::orderBy('filter_order', 'desc')->first();
    // return $saved_data;
    $master = Master::find($master_id);
    $data = [
      'master' => $master,
      'filter' => $filter,
      'filter_parent' => $filter_parent ?? null,
      'saved_data' => $saved_data_arr,
      'last_data' => $max_order->filter_order == $filter->filter_order ? 1 : 0,
    ];
    // return $data;
    return view('content.utilities.masters.addDetails', $data);
  }

  public function storeDetails(Request $request)
  {
    // return $request->all();
    $validator = FacadesValidator::make($request->all(), [
      'master_id' => 'required|exists:masters,id',
      'Filter_id' => 'required|exists:filters,id',
      'filter_parent_id' => 'nullable|exists:filters,id',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      foreach ($errors->all() as $error) {
        Toastr::error('Validation error: ' . $error, 'Error');
      }
      return redirect()
        ->back()
        ->withInput();
    }

    if ($request->filter_parent_id) {
      $breadcrumb = $request->master_id . '>' . $request->Filter_id . '>' . $request->Filter_parent_id;
    } else {
      $breadcrumb = $request->master_id . '>' . $request->Filter_id;
    }

    $filterValues = $request->filterValue;
    foreach ($filterValues as $value_id => $genderValues) {
      MasterData::create([
        'master_id' => $request->master_id,
        'filter_id' => $request->Filter_id,
        'parent_value_id' => $request->Filter_parent_id ?? $value_id,
        'filter_value_id' => $value_id,
        'male' => $genderValues['male'],
        'female' => $genderValues['female'],
        'other' => $genderValues['other'],
        'breadcrumb' => $breadcrumb,
      ]);
    }

    Toastr::success('Details stored successfully', 'Success');
    return redirect()->back();
  }

  public function loadFilterValues($filterValueId, $master_id)
  {
    // return $filterValueId;
    $filterValue = FilterValue::findOrFail($filterValueId);
    $filter = Filter::find($filterValue->filter_id);

    // Handle comma-separated parent_master (optional logic to adjust if needed)
    if (strpos($filter->parent_master, ',')) {
      $str = explode(',', $filter->parent_master);
      $first = $str[0];
      $filter = Filter::find($first);
    }

    // Keep going up until filter_order == 0
    while ($filter && $filter->filter_order == 0) {
      $filter = Filter::find($filter->parent_id);
    }

    $order = $filter->filter_order - 1;

    $filter_parent = Filter::with('filter_values')
      ->where('filter_order', $order)
      ->first();
    if ($filter_parent->child == 1) {
      $childFilters = Filter::whereRaw('FIND_IN_SET(?, parent_master)', [$filter_parent->id])->get();
      $childValues = [];
      foreach ($childFilters as $childFilter) {
        $values = FilterValue::where('filter_id', $childFilter->id)->get();
        $childValues = array_merge($childValues, $values->toArray());
      }
      $filterValues = collect($childValues); // Convert to collection for consistency
    } else {
      $filterValues = $filter_parent->filter_values;
    }

    // return gettype($filterValues);
    // Optional: load saved data if exists
    $savedData = []; // get saved data from your logic here
    foreach ($filterValues as $child) {
      // return $child;
      $data = MasterData::where('parent_value_id', $filterValueId)
        // ->where('filter_id', $filterValue->filter_id)
        ->where('master_id', $master_id)
        ->get();
      if ($data->isNotEmpty()) {
        foreach ($data as $item) {
          $savedData[$item->filter_value_id] = [
            'male' => $item->male,
            'female' => $item->female,
            'other' => $item->other,
          ];
        }
      }
    }
    // return $savedData;
    return response()->json([
      'data' => $filterValues->map(function ($child) use ($savedData) {
        $id = $child['id'];
        return [
          'id' => $id,
          'title' => $child['title'],
          'male' => $savedData[$id]['male'] ?? 0,
          'female' => $savedData[$id]['female'] ?? 0,
          'other' => $savedData[$id]['other'] ?? 0,
        ];
      }),
      'savedData' => count($savedData) > 0 ? true : false,
    ]);
  }

  public function Syncdata($master_id)
  {
    if ($master_id != 0) {
      if (MasterLocationDetail::where('master_id', $master_id)->exists()) {
        Toastr::warning('warning', 'Master Data already synced .Please create new master');
        return redirect()->route('utilities.masters.index');
      }
      $filterValues = FilterValue::pluck('id')->toArray();
      $saved_data = MasterData::where('master_id', $master_id)
        ->pluck('parent_value_id')
        ->toArray();
      $diff = array_diff($filterValues, $saved_data);
      if (count($diff) > 0) {
        Toastr::error('Please Check some Filter data is left to be added');
        return redirect()->route('utilities.masters.index');
      } else {
        $locations = Location::all();
        $master_data = MasterData::where('master_id', $master_id)->get();
        try {
          foreach ($locations as $location) {
            foreach ($master_data as $item) {
              $male = $location->male;
              $female = $location->female;
              $other = $location->other;
              if ($male == 0 && $female == 0 && $other == 0) {
                $locationchilds = Location::whereRaw('FIND_IN_SET(?, parent_master)', [$location->id])->get();
                foreach ($locationchilds as $locationchild) {
                  $male += $locationchild->male;
                  $female += $locationchild->female;
                  $other += $locationchild->other;
                }
              }
              MasterLocationDetail::create([
                'master_id' => $master_id,
                'filter_id' => $item->filter_id,
                'parent_value_id' => $item->parent_value_id,
                'filter_value_id' => $item->filter_value_id,
                'location_id' => $location->id,
                'parent_locations' => $location->parent_master,
                'male' => $male * ($item->male / 100),
                'female' => $female * ($item->female / 100),
                'other' => $other * ($item->other / 100),
              ]);
            }
          }
          Toastr::success('success', 'Master Data Successfully synced');
          return redirect()->route('utilities.masters.index');
        } catch (Exception $e) {
          return $e;
        }
      }
    } else {
      Toastr::error('something went wrong');
      return redirect()->route('utilities.masters.index');
    }
  }
}
