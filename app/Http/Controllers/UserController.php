<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yoeunes\Toastr\Facades\Toastr;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('status','!=','deleted')->get();
        $permissions = Permission::all();

        return view('content.pages.userlist', ['users' => $users,'permissions'=> $permissions]);
    }

    public function store(Request $request)
    {
      // dd($request->all());
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'legal_entity' => 'required',
            'company_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users,phone',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Toastr::error($error, 'Validation Error');
            }
            return redirect()->back()->withInput();
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => $request->role,
            'legal_entity' => $request->legal_entity,
            'company_name' => $request->company_name,
            'country' => $request->country,
            'phone' => $request->phone,
            'status' => 'active',
            'password' => Hash::make($request->password),
        ]);

        Toastr::success('User added successfully.','success' );
        return redirect()->back();
    }

    public function archive(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if($user->status === 'archived') {
        $user->status = 'active';
        $user->save();

        Toastr::success( 'User unarchived successfully.','success' );

            return redirect()->back();
        }
        $user->status = 'archived';
        $user->save();

        Toastr::success( 'User archived successfully.','success' );
        return redirect()->back();
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'deleted';
        $user->save();

        Toastr::success( 'User deleted successfully.','success' );
        return redirect()->back();
    }
}
