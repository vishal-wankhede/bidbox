<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\FilterValue;
use App\Models\LocationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Finder;
use Yoeunes\Toastr\Facades\Toastr;

class FilterController extends Controller
{
  //
  public function index($parent_id = null)
  {
    $data['parent_id'] = $parent_id;
    if ($parent_id != null) {
      $data['filter'] = $filter = Filter::where('id', $parent_id)->first();
      if ($filter->child == 0) {
        $data['filter_values'] = FilterValue::where('filter_id', $parent_id)->get();
      } else {
        $data['filters'] = Filter::where('parent_id', $parent_id)->get();
      }
    } else {
      $data['filters'] = Filter::where('parent_id', null)->get();
    }
    // return $data;
    return view('content.utilities.filters.index', $data);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|unique:filters,title|max:255',
      'isFix' => 'required|numeric',
      'child' => 'required|numeric',
      'parent_id' => 'numeric',
    ]);
    if ($validator->fails()) {
      Toastr::error($validator->errors()->first(), 'Error');
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }
    $parent_master = null;

    if ($request->parent_id) {
      $filter_master = Filter::find($request->parent_id);
      if ($filter_master->parent_master != null) {
        $parent_master = $filter_master->parent_master . ',' . $filter_master->id;
      } else {
        $parent_master = $request->parent_id;
      }
    }
    $filter = Filter::create([
      'title' => $request->title,
      'isFix' => $request->isFix,
      'child' => $request->child,
      'parent_master' => $parent_master,
      'parent_id' => $request->parent_id != 0 ? $request->parent_id : null,
    ]);

    if ($filter) {
      Toastr::success('Filter created successfully', 'Success');
    } else {
      Toastr::error('Failed to create filter', 'Error');
    }

    return redirect()->back();
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|max:255',
      'isFix' => 'required',
    ]);
    if ($validator->fails()) {
      Toastr::error($validator->errors()->first(), 'Error');
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }
    $filter = Filter::findOrFail($id);
    if (!$filter) {
      Toastr::error('Filter not found', 'Error');
      return redirect()->route('utilities.filters');
    }

    // Update the filter
    $filter->title = $request->title;
    $filter->isFix = $request->isFix;
    $filter->save();

    if ($filter) {
      Toastr::success('Filter updated successfully', 'Success');
    } else {
      Toastr::error('Failed to update filter', 'Error');
    }

    return redirect()->route('utilities.filters');
  }

  public function changeStatus($id)
  {
    $filter = Filter::findOrFail($id);
    if (!$filter) {
      Toastr::error('Filter not found', 'Error');
      return redirect()->route('utilities.filters');
    }
    if ($filter->status == 'active') {
      $filter->status = 'inactive';
      Toastr::success('Filter deactivated successfully', 'Success');
    } else {
      $filter->status = 'active';
      Toastr::success('Filter activated successfully', 'Success');
    }
    $filter->save();

    return redirect()->route('utilities.filters');
  }

  public function delete($id)
  {
    $filter = Filter::findOrFail($id);
    if (!$filter) {
      Toastr::error('Filter not found', 'Error');
      return redirect()->route('utilities.filters');
    }

    // Delete the filter
    $filter->delete();

    Toastr::success('Filter deleted successfully', 'Success');
    return redirect()->route('utilities.filters');
  }

  public function deleteFilterVal($id)
  {
    $filter = FilterValue::findOrFail($id);
    if (!$filter) {
      Toastr::error('FilterValue not found', 'Error');
      return redirect()->route('utilities.filters');
    }

    // Delete the filter
    $filter->delete();

    Toastr::success('Filter Value deleted successfully', 'Success');
    return redirect()->back();
  }

  public function storeFilterVal(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|unique:filter_values,title|max:255',
      'filter_id' => 'required|numeric',
    ]);
    if ($validator->fails()) {
      Toastr::error($validator->errors()->first(), 'error');
      return redirect()->back();
    }
    $filtervalue = FilterValue::create([
      'title' => $request->title,
      'filter_id' => $request->filter_id,
    ]);
    if ($filtervalue) {
      Toastr::success('Filter value added successfully', 'success');
      return redirect()->back();
    } else {
      Toastr::error('Somethiong went wrong', 'error');
      return redirect()->back();
    }
  }
  public function setOrder($id, Request $request)
  {
    $validator = Validator::make($request->all(), [
      'order' => 'required|numeric|max:255',
    ]);
    if ($validator->fails()) {
      Toastr::error($validator->errors()->first(), 'Error');
      return redirect()->back();
    }
    $filter = Filter::findOrFail($id);
    if (!$filter) {
      Toastr::error('Filter not found', 'Error');
      return redirect()->route('utilities.filters');
    } else {
      $filter->filter_order = $request->order;
      $filter->save();
      Toastr::success('Order set successfull', 'success');
      return redirect()->back();
    }
  }
  public function updateFilterVal(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|max:255',
    ]);
    if ($validator->fails()) {
      Toastr::error($validator->errors()->first(), 'Error');
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }
    $filter = FilterValue::findOrFail($id);
    if (!$filter) {
      Toastr::error('Filter not found', 'Error');
      return redirect()->route('utilities.filters');
    }

    // Update the filter
    $filter->title = $request->title;
    $filter->save();

    if ($filter) {
      Toastr::success('Filter Value updated successfully', 'Success');
    } else {
      Toastr::error('Failed to update filter value', 'Error');
    }

    return redirect()->back();
  }

  public function getfiltervalues($id, Request $request)
  {
    $values = FilterValue::where('filter_id', $id)
      ->select('id', 'title')
      ->get();

    // If gender_id and location_id are present
    if ($request->has(['gender_id', 'location_id'])) {
      $locationDetails = LocationDetail::where('location_id', $request->location_id)
        ->where('gender_id', $request->gender_id)
        ->where('filter_id', $id)
        ->pluck('population_value', 'filter_value_id');

      $values = $values->map(function ($item) use ($locationDetails) {
        $item->values = $locationDetails[$item->id] ?? null;
        return $item;
      });
    } else {
      $values = $values->map(function ($item) {
        $item->values = null;
        return $item;
      });
    }

    return response()->json($values);
  }

  // UtilityController.php
  public function getChildFilters($parentId)
  {
    $childFilters = Filter::where('parent_id', $parentId)->get(['id', 'title', 'child']);
    return response()->json($childFilters);
  }

  private function getChildrenWithValues($parentId)
  {
    $filters = Filter::where('parent_id', $parentId)->get(['id', 'title', 'child']);
    $result = [];

    foreach ($filters as $filter) {
      if ($filter->child == 0) {
        $values = FilterValue::where('filter_id', $filter->id)->get(['id', 'title']);
        $result[$filter->title] = $values;
      } else {
        $result[$filter->title] = $this->getChildrenWithValues($filter->id);
      }
    }

    return $result;
  }

  public function getDivisionChildren($parentId)
  {
    $result = $this->getChildrenWithValues($parentId);
    $label = Filter::find($parentId);

    return response()->json([
      'label' => $label->title,
      'values' => $result,
    ]);
  }
}
