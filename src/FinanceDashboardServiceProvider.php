<?php

namespace Breuermarcel\FinanceDashboard;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class FinanceDashboardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__."/../resources/lang", "finance-dashboard");
        $this->loadViewsFrom(__DIR__ . "/../resources/views", "finance-dashboard");
        $this->loadMigrationsFrom(__DIR__ . "/../database/migrations");
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . "/../config/config.php" => config_path("finance-dashboard.php"),
            ], "config");

            // Publishing the views.
            /*$this->publishes([
                __DIR__."/../resources/views" => resource_path("views/vendor/finance-dashboard"),
            ], "views");*/

            // Publishing assets.
            $this->publishes([
                __DIR__."/../resources/assets" => public_path("vendor/finance-dashboard"),
            ], "assets");

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__."/../resources/lang" => resource_path("lang/vendor/finance-dashboard"),
            ], "lang");*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . "/../config/config.php", "finance-dashboard");

        // Register the main class to use with the facade
        $this->app->singleton("finance-dashboard", function () {
            return new FinanceDashboard;
        });
    }

    protected function routeConfiguration()
    {
        return [
            "prefix" => config("finance-dashboard.routing.prefix"),
            "middleware" => config("finance-dashboard.routing.middleware"),
        ];
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . "/routes.php");
        });
    }
}
