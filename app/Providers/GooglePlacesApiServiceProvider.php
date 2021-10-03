<?php

namespace App\Providers;

use App\GooglePlacesApi\GooglePlacesApi;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class GooglePlacesApiServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GooglePlacesApi::class, function ($app) {
            return new GooglePlacesApi(env("GOOGLE_API_KEY"));
        });
    }
}
