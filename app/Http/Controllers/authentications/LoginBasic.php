<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginBasic extends Controller
{
  // Show login form
  public function index()
  {
    // If already logged in, redirect to dashboard
    if (Auth::check()) {
      return redirect()->route('dashboard');
    }

    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

  // Handle login POST request
<<<<<<< HEAD
  public function login(Request $request)
  {
    $credentials = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if ($credentials->fails()) {
      return $credentials->errors();
      return back()
        ->withErrors([
          'email' => 'Invalid credentials.',
        ])
        ->onlyInput('email');
    } else {
      if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $request->session()->regenerate();
        $user = DB::table('users')
          ->where('email', $request->email)
          ->first();

        $permissions = DB::table('permission_user')
          ->join('permissions', 'permissions.id', '=', 'permission_user.permission_id')
          ->where('permission_user.user_id', $user->id)
          ->pluck('permissions.name')
          ->toArray();

        Session::put('user_permissions', $permissions);
        return redirect()->intended(route('dashboard'));
      }
    }
  }
=======
public function login(Request $request)
{

    // Validate input
    $credentials = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($credentials->fails()) {

        return back()
            ->withErrors($credentials) // Show validation errors
            ->withInput();
    }

    // Try login
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $request->session()->regenerate();

        $user = DB::table('users')
            ->where('email', $request->email)
            ->first();

        $permissions = DB::table('permission_user')
            ->join('permissions', 'permissions.id', '=', 'permission_user.permission_id')
            ->where('permission_user.user_id', $user->id)
            ->pluck('permissions.name')
            ->toArray();

        Session::put('user_permissions', $permissions);

        return redirect()->intended(route('dashboard'));
    }

    // If login failed
    return back()
        ->withErrors([
            'email' => 'Incorrect email or password.',
        ])
        ->withInput();
}

>>>>>>> 8ecc85ec2fb9a7f7e6b352750a47589f9882aaba

  // Handle logout
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('auth-login-basic');
  }
}
