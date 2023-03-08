<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedsController;
use App\Http\Controllers\EntriesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Shows home page
Route::get('/', function() {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/feeds/', [FeedsController::class, 'index'])->name('feeds');
    Route::get('/feeds/create', [FeedsController::class, 'create'])->name('feeds.create');
    Route::post('/feeds', [FeedsController::class, 'store']);
    Route::get('/feeds/{feed}', [FeedsController::class, 'show'])->name('feeds.show');
    Route::get('/feeds/{feed}/edit', [FeedsController::class, 'edit'])->name('feeds.edit');
    Route::put('/feeds/{feed}', [FeedsController::class, 'update']);
    Route::delete('/feeds/{feed}', [FeedsController::class, 'destroy']);

    //Shows all entries 
    // TODO: sort by date and add pagination to only show the X most recent
    //Route::get('/entries', [EntriesController::class, 'index']);
    Route::get('/entry/{entry}', [EntriesController::class, 'show'])->name('entries.show');
});

require __DIR__.'/auth.php';
