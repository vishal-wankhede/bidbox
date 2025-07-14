<?php

namespace App\Http\Controllers;

use App\Models\AgeRange;
use App\Models\Country;
use App\Models\Device;
use App\Models\Gender;
use App\Models\State;
use App\Models\StatePopulationSegment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yoeunes\Toastr\Facades\Toastr;

class UtilityController extends Controller
{
    public function demographics()
    {
        $data = [
            'countries' => Country::orderBy('name')->get(),
            'states' => State::with('country')->orderBy('name')->get(),
        ];
        // return $data;
        return view('content.utilities.demographics',$data); // create this view
    }

    public function addCountry(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'population' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Toastr::error($error, 'Validation Error');
            }
            return redirect()->back()->withInput();
        }
        Country::create([
            'name' => $request->name,
            'population' => $request->population,
        ]);

        Toastr::success('Country added successfully.','success' );
        return redirect()->back();
    }

    public function deleteCountry($id)
    {
        $country = Country::find($id);
        if (!$country) {
            Toastr::error('Country not found.', 'Error');
            return redirect()->back();
        }
        $country->delete();
        Toastr::success('Country deleted successfully.', 'Success');
        return redirect()->back();
    }

    public function updateCountry($id)
    {
        $country = Country::find($id);
        if (!$country) {
            Toastr::error('Country not found.', 'Error');
            return redirect()->back();
        }
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'population' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Toastr::error($error, 'Validation Error');
            }
            return redirect()->back()->withInput();
        }
        $country->name = request()->name;
        $country->population = request()->population;
        $country->save();
        Toastr::success('Country updated successfully.', 'Success');
        return redirect()->back();
    }

        public function division()
    {
        $data = [
            'ageRanges' => AgeRange::orderBy('name')->get(),
            'genders' => Gender::orderBy('name')->get(),
            'devices' => Device::orderBy('name')->get()
        ];
        return view('content.utilities.division',$data); // create this view
    }

    public function storeAgeRange(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        AgeRange::create(['name' => $request->name]);
        return back()->with('success', 'Age Range added.');
    }

    public function deleteAgeRange($id)
    {
        AgeRange::destroy($id);
        return back()->with('success', 'Age Range deleted.');
    }

    public function storeDevice(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        Device::create(['name' => $request->name]);
        return back()->with('success', 'Device added.');
    }

    public function deleteDevice($id)
    {
        Device::destroy($id);
        return back()->with('success', 'Device deleted.');
    }

    public function storeGender(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        Gender::create(['name' => $request->name]);
        return back()->with('success', 'Gender added.');
    }

    public function deleteGender($id)
    {
        Gender::destroy($id);
        return back()->with('success', 'Gender deleted.');
    }

    public function addstate($mode,Request $request)
    {
        $countries = Country::all();
        $ageRanges = AgeRange::all();
        $genders = Gender::all();
        $devices = Device::all();
      if($mode == 'add') {
        $state = null;
        return view('content.utilities.add_new_state', compact('state','countries', 'ageRanges', 'genders', 'devices', 'mode'));
      }else if($mode == 'edit') {
        $state = State::where('id',$request->id)->with('segments')->first();
        foreach ($state->segments as $segment) {
        $segments[$segment->type][$segment->ref_id] = $segment->percentage;
        }
        if($state == null) {
            Toastr::error('State not found.', 'Error');
            return redirect()->back();
        }
        return view('content.utilities.add_new_state',compact('segments','countries', 'ageRanges', 'genders', 'devices', 'mode','state'));
      }
    }

    public function createstate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'population_percentage' => 'required|numeric|min:0|max:100',
            'age' => 'array',
            'gender' => 'array',
            'device' => 'array',
          ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Toastr::error($error, 'Validation Error');
            }
            return redirect()->back()->withInput();
        }
        // return $request->all();
        $state = State::create([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'population_percentage' => $request->population_percentage,
        ]);
        $segments = [];

        foreach (['age', 'gender', 'device'] as $type) {
            foreach ($request->input($type, []) as $refId => $percent) {
                if ($percent !== null && $percent !== '') {
                    $segments[] = [
                        'state_id' => $state->id,
                        'type' => $type,
                        'ref_id' => $refId,
                        'percentage' => $percent,
                    ];
                }
            }
        }

        StatePopulationSegment::insert($segments);
        Toastr::success('State created successfully.', 'Success');
        return redirect()->route('utilities.demographics');
      }

    public function leftPercentage($id)
    {
        $state = State::where('country_id', $id)->sum('population_percentage');
        $leftPercentage = max(0,100 - $state);
        return response()->json(['leftPercentage' => $leftPercentage]);
    }

    public function inventories()
    {
        return view('content.utilities.inventories'); // create this view
    }

    public function getStates($countryId)
{
    $states = State::where('country_id', $countryId)->get(['id', 'name']);
    return response()->json($states);
}
}
