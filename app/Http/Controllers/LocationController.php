<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\FilterValue;
use App\Models\Location;
use App\Models\LocationDetail;
use Exception;
use Hamcrest\Arrays\IsArray;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Foreach_;
use Yoeunes\Toastr\Facades\Toastr;

class LocationController extends Controller
{
  //
  public function index($parent_id = null)
  {
    $data['parent_id'] = $parent_id;
    if (!$parent_id) {
      $data['locations'] = Location::where('parent', null)->get();
    } else {
      $data['locations'] = Location::where('parent', $parent_id)->get();
    }
    return view('content.utilities.locations.index', $data);
  }

  public function create($parent_id = null)
  {
    return view('content.utilities.locations.create', ['parent_id' => $parent_id]);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'parent_id' => 'nullable|exists:locations,id',
      'child' => 'nullable|min:0',
      'child_name' => 'nullable|string',
      'male' => 'nullable|integer|min:0',
      'female' => 'nullable|integer|min:0',
      'other' => 'nullable|integer|min:0',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $error) {
        Toastr::error($error, 'Error');
      }
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $parent_master = null;

    if ($request->parent_id) {
      $location_master = Location::find($request->parent_id);
      if ($location_master->parent_master != null) {
        $parent_master = $location_master->parent_master . ',' . $location_master->id;
      } else {
        $parent_master = $location_master->id;
      }
    }

    $location = Location::create([
      'name' => $request->name,
      'parent' => $request->parent_id ?? null,
      'child' => $request->child,
      'child_name' => $request->child_name,
      'parent_master' => $parent_master,
      'male' => $request->male ?? 0,
      'female' => $request->female ?? 0,
      'other' => $request->other ?? 0,
    ]);

    if ($location) {
      Toastr::success('Location created successfully', 'Success');
      return redirect()->route('utilities.locations', ['parent_id' => $request->parent_id]);
    } else {
      Toastr::error('Failed to create location', 'Error');
      return redirect()
        ->back()
        ->withInput();
    }
  }

  private function getChildrenFilterValueDetails($location_id, $gender_id, $filter)
  {
    $result = [];

    if ($filter->child == 0) {
      $filterValues = FilterValue::where('filter_id', $filter->id)->get();

      foreach ($filterValues as $filterValue) {
        $population = LocationDetail::where('location_id', $location_id)
          ->where('filter_value_id', $filterValue->id)
          ->where('gender_id', $gender_id)
          ->pluck('population_value')
          ->first(); // assuming single value

        $result[$filterValue->title] = $population ?? 0;
      }
    } else {
      $childFilters = Filter::where('parent_id', $filter->id)
        ->orderBy('filter_order')
        ->get();

      foreach ($childFilters as $childFilter) {
        $result[$childFilter->title] = $this->getChildrenFilterValueDetails($location_id, $gender_id, $childFilter);
      }
    }

    return $result;
  }

  private function getChildrenFilterDetails($location_id, $gender_id)
  {
    $filters = Filter::whereNull('parent_id')
      ->orderBy('filter_order')
      ->get();
    $result = [];

    foreach ($filters as $filter) {
      $result[$filter->title] = $this->getChildrenFilterValueDetails($location_id, $gender_id, $filter);
    }

    return $result;
  }

  private function getChildren($parentId)
  {
    $locations = Location::where('parent', $parentId)->get();
    $result = [];

    foreach ($locations as $location) {
      if ($location->child == 0) {
        $result[$location->name] = [
          'male' => [
            'value' => $location->male,
            'filters' => $this->getChildrenFilterDetails($location->id, 1),
          ],
          'female' => [
            'value' => $location->female,
            'filters' => $this->getChildrenFilterDetails($location->id, 2),
          ],
          'other' => [
            'value' => $location->other,
            'filters' => $this->getChildrenFilterDetails($location->id, 3),
          ],
        ];
      } else {
        $result[$location->name] = $this->getChildren($location->id);
      }
    }

    return $result;
  }

  public function view($id)
  {
    $location = Location::findOrFail($id);

    $data['location'] = $location;
    $data['children'] = [$location->name => $this->getChildren($location->id)];
    return view('content.utilities.locations.view', $data);
  }

  public function addDetails($id)
  {
    $location = Location::findOrFail($id);
    if (!$location) {
      Toastr::error('Location not found', 'Error');
      return redirect()->back();
    }
    if ($location->child == 1) {
      $total = 0;
      $male = 0;
      $female = 0;
      $other = 0;

      // Get all children locations
      $children = Location::where('parent', $location->id)->get();
      if (!empty($children)) {
        foreach ($children as $child) {
          $total += $child->total;
          $male += $child->male;
          $female += $child->female;
          $other += $child->other;
        }

        // Update the parent location with the aggregated values
        $location->update([
          'total' => $total,
          'male' => $male,
          'female' => $female,
          'other' => $other,
        ]);
      }
    }
    return view('content.utilities.locations.details', compact('location'));
  }

  public function updateDetails(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'parent_id' => 'nullable|exists:locations,id',
      'child' => 'nullable|min:0',
      'child_name' => 'nullable|string',
      'total' => 'nullable|integer|min:0',
      'male' => 'nullable|integer|min:0',
      'female' => 'nullable|integer|min:0',
      'other' => 'nullable|integer|min:0',
    ]);
    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $error) {
        Toastr::error($error, 'Error');
      }
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput();
    }

    $location = Location::findOrFail($id);
    if (!$location) {
      Toastr::error('Location not found', 'Error');
      return redirect()->back();
    }

    $location->update([
      'parent' => $request->parent_id ?? null,
      'child' => $request->child ?? 0,
      'child_name' => $request->child_name ?? '',
      'total' => $request->total ?? 0,
      'male' => $request->male ?? 0,
      'female' => $request->female ?? 0,
      'other' => $request->other ?? 0,
    ]);

    Toastr::success('Location updated successfully', 'Success');
    return redirect()->route('utilities.locations', ['parent_id' => $request->parent_id]);
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'child' => 'required|in:0,1',
      'child_name' => 'nullable|string',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors() as $error) {
        Toastr::error($error, 'error');
      }
      return redirect()->back();
    }

    $location = Location::findOrFail($id);
    if (!$location) {
      Toastr::error('Location not found', 'Error');
      return redirect()->back();
    }
    $location->name = $request->name;
    $location->child = $request->child;
    $location->child_name = $request->child_name;
    $location->save();

    // If it's a normal web request
    Toastr::success('updated successfully', 'success');
    return redirect()->back();
  }

  public function destroy($id)
  {
    $location = Location::findOrFail($id);
    if (!$location) {
      Toastr::error('Location not found', 'Error');
      return redirect()->back();
    }
    $location->delete();

    Toastr::success('Location deleted successfully', 'Success');
    return redirect()->back();
  }

  public function addFilterDetails($id, $gender_id, Request $request)
  {
    // return $request;
    $filter_id = $request->filter_id ?? 0;
    $data['filter_value_id'] = $filterValueIds = $request->filter_value_id
      ? (is_array($request->filter_value_id)
        ? $request->filter_value_id
        : json_decode($request->filter_value_id, true))
      : [];

    $data['location'] = Location::find($id);
    if (!$data['location']) {
      Toastr::error('something went wrong', 'error');
    }
    if ($filter_id == 0) {
      $data['filter'] = Filter::where('filter_order', 1)->first();
      if (!$data['filter']) {
        Toastr::error('Filter not found', 'error');
        return redirect()->route('utilities.locations', $data['location']->id);
      }
      if ($data['filter']->child == 1) {
        $filterController = new FilterController();
        $data1 = $filterController->getDivisionChildren($data['filter']->id);
        $dataArray = $data1->getData(true); // true = convert stdClass to array
        $data['children'] = $dataArray['values'];
      } else {
        $data['filter_values'] = FilterValue::where('filter_id', $data['filter']->id)->get();
        foreach ($data['filter_values'] as $value) {
          $locationdetails = LocationDetail::where('location_id', $id)
            ->where('gender_id', $gender_id)
            ->where('filter_value_id', $value->id)
            ->first();
          $value->population_value = $locationdetails ? $locationdetails->population_value : 0;
        }
      }
    } else {
      $prev_filter = Filter::find($filter_id);
      if (count($filterValueIds) > 0) {
        $order = $prev_filter->filter_order + 1;
      } else {
        $order = $prev_filter->filter_order;
      }
      // return[$prev_filter->filter_order, $order];
      $data['filter'] = Filter::where('filter_order', $order)->first();
      if (!$data['filter']) {
        Toastr::success('The data for this ' . $data['location']->title . ' is successfully saved', 'success');
        return redirect()->route('utilities.locations', $data['location']->id);
      }
      if ($data['filter']->child == 1) {
        $filterController = new FilterController();
        $data1 = $filterController->getDivisionChildren($data['filter']->id);
        $dataArray = $data1->getData(true); // true = convert stdClass to array
        $data['children'] = $dataArray['values'];
        foreach ($data['children'] as $group => $items) {
          foreach ($items as $index => $child) {
            // return $child;
            if (is_array($child) && isset($child['id'])) {
              $locationdetails = LocationDetail::where('location_id', $id)
                ->where('gender_id', $gender_id)
                ->where('filter_value_id', $child['id'])
                ->first();

              $data['children'][$group][$index]['population_value'] = $locationdetails
                ? $locationdetails->population_value
                : 0;
              // return $data['children'];
            } elseif (is_array($child)) {
              foreach ($child as $i => $c) {
                $locationdetails = LocationDetail::where('location_id', $id)
                  ->where('gender_id', $gender_id)
                  ->where('filter_value_id', $c['id'])
                  ->first();

                $data['children'][$group][$index][$i]['population_value'] = $locationdetails
                  ? $locationdetails->population_value
                  : 0;
              }
              // return $data['children'];
            } else {
              return 'error';

              logger()->warning('Unexpected child format', ['child' => $child]);
            }
          }
        }

        // return $data;
      } else {
        $data['filter_values'] = FilterValue::where('filter_id', $data['filter']->id)->get();
        foreach ($data['filter_values'] as $value) {
          $locationdetails = LocationDetail::where('location_id', $id)
            ->where('gender_id', $gender_id)
            ->where('filter_value_id', $value->id)
            ->first();
          $value->population_value = $locationdetails ? $locationdetails->population_value : 0;
        }
        // return $data['filter_values'];
      }
    }
    $data['gender_id'] = $gender_id;
    $data['gender'] = $gender_id == 1 ? 'male' : ($gender_id == 2 ? 'female' : 'other');
    $data['last_node'] = Filter::orderBy('filter_order', 'desc')->first();
    // return $data;
    return view('content.utilities.locations.filterdetails', $data);
  }

  public function storefiltervalues(Request $request)
  {
    // return $request;
    $validator = Validator::make($request->all(), [
      'location_id' => 'required|numeric',
      'gender_id' => 'required|numeric',
      'filter_id' => 'required|numeric',
      'values' => 'required|array',
      'filter_value_id' => 'array',
    ]);
    if ($validator->fails()) {
      return $validator->errors();
      foreach ($validator->errors() as $error) {
        Toastr::error($error, 'error');
      }
      return redirect()->back();
    }
    try {
      if ($request->location_id != null) {
        $location = Location::find($request->location_id);
      }
      $values = $request->values;
      foreach ($values as $filter => $value) {
        LocationDetail::create([
          'location_id' => $request->location_id,
          'gender_id' => $request->gender_id,
          'filter_id' => $request->filter_id,
          'filter_value_id' => $filter,
          'parent_locations' => $location ? $location->parent_master . ',' . $location->id : $request->location_id,
          'parent_detail_id' => is_array($request->filter_value_id)
            ? implode(',', $request->filter_value_id)
            : $request->filter_value_id,

          'population_value' => $value,
        ]);
      }
      Toastr::success('successfully added', 'success');
      return redirect()->route('utilities.locations.addFilterDetails', [
        'id' => $request->location_id,
        'gender_id' => $request->gender_id,
      ]) .
        '?' .
        http_build_query([
          'filter_id' => $request->filter_id,
          'filter_value_id' => json_encode($request->filter_value_id ?? []),
        ]);
    } catch (Exception $e) {
      Toastr::error('something went wrong', 'error');
      return redirect()->back();
    }
  }

  public function getChildLocations(Request $request)
  {
    $request->validate([
      'parent_ids' => 'required|array|min:1',
    ]);

    $childLocations = [];
    $totalPopulation = 0;
    $child_name = 'unknown child';

    $parents = Location::whereIn('id', $request->parent_ids)->get();

    foreach ($parents as $parent) {
      $child_name = $parent->child_name;
      $children = Location::where('parent', $parent->id)->get();
      foreach ($children as $child) {
        $childLocations[] = $child;
      }

      // If no children, add this node's population
      if ($children->isEmpty()) {
        $totalPopulation += $parent->male + $parent->female + $parent->other;
      }
    }

    // If children exist, accumulate their population too
    foreach ($childLocations as $child) {
      $totalPopulation += $child->male + $child->female + $child->other;
    }

    return response()->json([
      'locations' => $childLocations,
      'population' => $totalPopulation,
      'child_name' => $child_name,
    ]);
  }

  public function getfilterdetails(Request $request)
  {
    $population = 0;

    $locations = Location::whereIn('id', json_decode($request->locations, true))
      ->where('child', 0)
      ->pluck('id');

    $locationdetails = 0;

    if ($request->filters && $request->gender) {
      $locationdetails = LocationDetail::whereIn('location_id', $locations)
        ->whereIn('gender_id', json_decode($request->gender, true))
        ->whereIn('filter_value_id', json_decode($request->filters, true))
        ->sum('population_value');
    } elseif ($request->gender) {
      $locations1 = Location::whereIn('id', json_decode($request->locations, true))
        ->where('child', 0)
        ->get();
      $gender = json_decode($request->gender, true);
      foreach ($locations1 as $child) {
        if (in_array(1, $gender)) {
          $locationdetails += $child->male;
        }
        if (in_array(2, $gender)) {
          $locationdetails += $child->female;
        }
        if (in_array(3, $gender)) {
          $locationdetails += $child->other;
        }
      }
    } else {
      $locations1 = Location::whereIn('id', json_decode($request->locations, true))
        ->where('child', 0)
        ->get();
      foreach ($locations1 as $child) {
        $locationdetails += $child->male + $child->female + $child->other;
      }
    }

    return response()->json($locationdetails);
  }
}
