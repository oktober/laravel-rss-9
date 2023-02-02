<?php

namespace App\Http\Controllers;


use App\Models\Entry;
use Illuminate\Http\Request;

class EntriesController extends Controller
{
    // I don't think we need this because we're use home.blade.php instead...
    /*public function index(){
    	$entries = Entry::get();
    	
    	return view('entries.index', ['entries' => $entries]);
    }*/

    public function show(Entry $entry){
    	return view('entries.show', ['entry' => $entry]);
    }
}
