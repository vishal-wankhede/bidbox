<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
    public function login(Request $request)
    {
      $credentials = Validator::make($request->all(),[
        'email' => 'required|email',
        'password' => 'required',
      ]);

      if($credentials->fails()){
        return $credentials->errors();
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
      }else{
        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
      }
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth-login-basic');
    }
}
