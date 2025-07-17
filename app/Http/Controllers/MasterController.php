<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\FilterValue;
use App\Models\Master;
use App\Models\MasterData;
use Dotenv\Validator;
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
    // return $saved_data;
    $master = Master::find($master_id);
    $data = [
      'master' => $master,
      'filter' => $filter,
      'filter_parent' => $filter_parent ?? null,
      'saved_data' => $saved_data_arr,
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
}
