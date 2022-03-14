<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Photo;
Use App\Observers\PhotoObserver;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Photo::observe(PhotoObserver::class);
    }
}
