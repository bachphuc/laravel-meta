<?php

namespace bachphuc\LaravelMeta\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'bachphuc\LaravelMeta\Http\Controllers';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $packagePath = dirname(__DIR__);

        $this->publishes([
            $packagePath .'/config/meta.php' => config_path('meta.php'),
        ], 'meta-config');

        // register view
        $this->loadViewsFrom($packagePath . '/resources/views', 'meta');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Register the service provider for the dependency.
         */

        $this->app->bind('laravel_meta', function(){
            return new \bachphuc\LaravelMeta\PageMeta();
        });
    }
}