<?php

namespace App\Providers;

use App\GooglePlacesApi\GooglePlacesApi;
use App\GooglePlacesApi\GooglePlacesApiLive;
use App\GooglePlacesApi\GooglePlacesApiMock;
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
        $recording = (bool) env('GOOGLE_PLACES_RECORDING', false);
        if (!$recording && env("APP_ENV") === "testing") {
            $this->app->singleton(GooglePlacesApi::class, function ($app) {
                return new GooglePlacesApiMock();
            });
        } else {
            $this->app->singleton(GooglePlacesApi::class, function ($app) {
                return new GooglePlacesApiLive(env("GOOGLE_API_KEY"));
            });
        }
    }
}
