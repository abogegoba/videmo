<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ReLab\Laravel\Http\Routing\StaticUrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $url = $this->app['url'];
        $this->app->singleton('url', function () use ($url) {
            return new StaticUrlGenerator($url);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
