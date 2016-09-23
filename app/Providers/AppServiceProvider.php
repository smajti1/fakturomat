<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            if (class_exists(Barryvdh\Debugbar\ServiceProvider::class)) {
                $this->app->register(Barryvdh\Debugbar\ServiceProvider::class);
            }
            if (class_exists(Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class)) {
                $this->app->register(Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            }
        }
    }
}
