<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AlbumController,
    HomeController,
    PhotoController,
    UserController,
    TagController
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
Route::get('user/{user}', [UserController::class, 'photos'])->name('user.photos');
Route::get('tag/{tag}', [TagController::class, 'photos'])->name('tag.photos');
Route::get('category/{category}', [CategoryController::class, 'photos'])->name('category.photos');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
//route de vue d'une photo
Route::get('photo/{photo}', [PhotoController::class, 'show'])->name('photos.show');
Route::get('read-all', [PhotoController::class, 'readAll'])->name('notifications.read')->middleware('auth', 'verified');
//route pour voter pour une photo
Route::get('vote/{photo}/{vote}/{token}', [PhotoController::class, 'vote'])->name('photo.vote');

// routes POST
Route::post('download', [PhotoController::class, 'download'])->name('photos.download')->middleware('auth', 'verified');

// routes resources
Route::resource('albums', AlbumController::class);

// routes groupes middleware
Route::middleware(['auth', 'verified'])->group(function() {


    // authentifié et vérifié

    Route::get('photos/create/{album}', [PhotoController::class, 'create'])->name('photos.create');
    Route::post('photos/store/{album}', [PhotoController::class, 'store'])->name('photos.store');

    // suppresion de la photo
    Route::delete('delete-photo/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    /*
    Route::get('user', function() {
        return auth()->user()->email_verified_at;
    });
    */

});

// import dossier des routes d'authentification
require __DIR__.'/auth.php';
