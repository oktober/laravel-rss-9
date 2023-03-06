<?php

namespace App\Http\Controllers;

use App\Models\Feed;

class DashboardController extends Controller
{
  public function index()
  {
    $feeds = Feed::with('entries')->get();

    return view('dashboard', ['feeds' => $feeds]);
  }
}
