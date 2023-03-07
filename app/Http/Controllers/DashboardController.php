<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index()
  {
    // show all feeds that belong to this user that have entries
    $feeds = Feed::whereBelongsTo(request()->user())->with('entries')->get();

    return view('dashboard', ['feeds' => $feeds]);
  }
}
