<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;

class HomePage extends Controller
{
  public function index()
  {
    $data['active_campaigns'] = Campaign::where('status', 'active')->count();
    $data['archived_campaigns'] = Campaign::where('status', 'archived')->count();
    $data['total_campaigns'] = Campaign::count();
    $data['active_users'] = User::where('status', 'active')->count();
    $data['archived_users'] = User::where('status', 'archived')->count();
    $data['total_users'] = User::count();
    return view('content.pages.pages-home',$data);
  }
}
