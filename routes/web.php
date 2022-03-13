<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AlbumController,
    HomeController,
    PhotoController
};

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


// routes GET
Route::get('/', HomeController::class)->name('home');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



// routes POST


// routes resources
Route::resource('albums', AlbumController::class);

// routes groupes middleware
Route::middleware(['auth', 'verified'])->group(function() {


    // authentifié et vérifié

    Route::get('photos/create/{album}', [PhotoController::class, 'create'])->name('photos.create');
    Route::post('photos/store/{album}', [PhotoController::class, 'store'])->name('photos.store');

    /*
    Route::get('user', function() {
        return auth()->user()->email_verified_at;
    });
    */

});

// import dossier des routes d'authentification
require __DIR__.'/auth.php';
