<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AlbumController,
    HomeController,
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


// import dossier des routes d'authentification
require __DIR__.'/auth.php';
