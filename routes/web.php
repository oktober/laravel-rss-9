<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FeedsController;
use App\Http\Controllers\EntriesController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

//Shows home page
Route::get('/', [HomeController::class, 'index']);

//Shows all feeds listed out - needs formatting
Route::get('/feeds', [FeedsController::class, 'index']);
//Shows form to link to a new feed
Route::get('/feeds/create', [FeedsController::class, 'create']);
//Handles the POST from /feeds/create to store the new feed
Route::post('/feeds', [FeedsController::class, 'store']);
//Shows one feed and all its entries
Route::get('/feeds/{feed}', [FeedsController::class, 'show'])->name('feeds.show');
//Shows form to edit feed details
Route::get('/feeds/{feed}/edit', [FeedsController::class, 'edit']);
//Handles PUT from /feeds/{feed}/edit to update the feed details
Route::put('/feeds/{feed}', [FeedsController::class, 'update']);
//Handles DELETE from /feeds/{feed}/edit to delete feed record & all entries
Route::delete('/feeds/{feed}', [FeedsController::class, 'destroy']);

//Shows all entries - will be good to sort by date and adding pagination to only show the X most recent
//Route::get('/entries', [EntriesController::class, 'index']);
Route::get('/entry/{entry}', [EntriesController::class, 'show']);
