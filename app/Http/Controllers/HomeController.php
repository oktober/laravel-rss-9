<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feed;

class HomeController extends Controller
{
    public function index() {
		$feeds = Feed::with('entries')->get();

    	return view('home', ['feeds' => $feeds]);
    }
}
