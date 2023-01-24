<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FeedsController;
use App\Http\Controllers\EntriesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Shows home page
Route::get('/', [HomeController::class, 'index']);

// Create a group for all the Feeds controllers
Route::controller(FeedsController::class)->group(function () {
    Route::get('/feeds', 'index');
    Route::get('/feeds/create', 'create');
    Route::post('/feeds', 'store');
    Route::get('/feeds/{feed}', 'show')->name('feeds.show');
    Route::get('/feeds/{feed}/edit', 'edit');
    Route::put('/feeds/{feed}', 'update');
    Route::delete('/feeds/{feed}', 'destroy');
});

//Shows all entries 
// TODO: sort by date and add pagination to only show the X most recent
//Route::get('/entries', [EntriesController::class, 'index']);
Route::get('/entry/{entry}', [EntriesController::class, 'show']);
