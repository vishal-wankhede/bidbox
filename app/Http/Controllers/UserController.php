<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yoeunes\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $users = User::where('status', '!=', 'deleted')->get();
    $permissions = Permission::all();
    $campaigns = Campaign::all();

    return view('content.pages.userlist', ['users' => $users, 'permissions' => $permissions,'campaigns' => $campaigns]);
  }

  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
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
        return redirect()
          ->back()
          ->withInput();
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

      if ($request->has('permissions')) {
        foreach ($request->permissions as $permissionId) {
          DB::table('permission_user')->insert([
            'user_id' => $user->id,
            'permission_id' => (int) $permissionId,
            'created_at' => now(),
            'updated_at' => now(),
          ]);
        }
      }
    if ($request->has('campaigns')) {
        foreach ($request->campaigns as $campaignId) {
          DB::table('assigned_campaigns')->insert([
            'user_id' => $user->id,
            'campaign_id' => (int) $campaignId,
            'created_at' => now(),
            'updated_at' => now(),
          ]);
        }
      }
      Toastr::success('User added successfully.', 'success');
      return redirect()->back();
    } catch (\Exception $e) {
      \Log::error('Error in UserController@store', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'request' => $request->all(),
      ]);

      Toastr::error('Something went wrong while creating user.', 'Error');
      return redirect()
        ->back()
        ->withInput();
    }
  }

  public function archive(Request $request, $id)
  {
    $user = User::findOrFail($id);
    if ($user->status === 'archived') {
      $user->status = 'active';
      $user->save();

      Toastr::success('User unarchived successfully.', 'success');

      return redirect()->back();
    }
    $user->status = 'archived';
    $user->save();

    Toastr::success('User archived successfully.', 'success');
    return redirect()->back();
  }

  public function destroy($id)
  {
    $user = User::findOrFail($id);
    $user->status = 'deleted';
    $user->save();

    Toastr::success('User deleted successfully.', 'success');
    return redirect()->back();
  }
}
