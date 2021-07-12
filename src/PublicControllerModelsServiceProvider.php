<?php

namespace hainguyen\decentralization;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
class PublicControllerModelsServiceProvider extends ServiceProvider
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
        $this->publishes([
            __DIR__.'\\Http\\controllers\\' => app_path('/Http/Controllers/'),
        ],'publicController');
        $this->publishes([
            __DIR__.'\\Models\\' => app_path('/'),
        ],'publicModel');
        Artisan::call('vendor:publish --tag="publicModel" --force');
        Artisan::call('vendor:publish --tag="publicController" --force');
    }
}
