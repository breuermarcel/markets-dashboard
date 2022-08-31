<?php

namespace Breuermarcel\FinanceDashboard;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;

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
        $this->loadBladeDirectives();
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            /*$this->publishes([
                __DIR__ . "/../config/config.php" => config_path("finance-dashboard.php"),
            ], "config");*/

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

    private function routeConfiguration()
    {
        return [
            "prefix" => config("finance-dashboard.routing.prefix"),
            "middleware" => config("finance-dashboard.routing.middleware"),
        ];
    }

    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . "/routes.php");
        });
    }

    private function loadBladeDirectives()
    {
        Blade::directive("fmt_money", function ($expression) {
            switch (config("app.locale")) {
                case "de":
                    return "
                        <?php
                            if ($expression > 100)
                                echo number_format($expression, 0, ',', '.') . '€';
                            else
                                echo number_format($expression, 2, ',', '.') . '€';
                        ?>
                    ";

                default:
                    return "
                        <?php
                            if ($expression > 100)
                                echo '$' . number_format($expression, 0);
                            else
                                echo '$' . number_format($expression, 2);
                        ?>
                    ";
            }
        });

        Blade::directive("fmt_number", function ($expression) {
            switch (config("app.locale")) {
                case "de":
                    return "
                        <?php
                            echo number_format($expression, 0, ',', '.');
                        ?>
                    ";

                default:
                    return "
                        <?php
                            echo number_format($expression, 0);
                        ?>
                    ";
            }
        });

        Blade::directive("fmt_decimal", function ($expression) {
            switch (config("app.locale")) {
                case "de":
                    return "
                        <?php
                            echo number_format($expression, 2, ',', '.');
                        ?>
                    ";

                default:
                    return "
                        <?php
                            echo number_format($expression, 2);
                        ?>
                    ";
            }
        });

        Blade::directive("fmt_percentage", function ($expression) {
            switch (config("app.locale")) {
                case "de":
                    return "
                        <?php
                            echo number_format($expression, 2, ',', '.') * 100 . '%';
                        ?>
                    ";

                default:
                    return "
                        <?php
                            echo number_format($expression, 2) * 100 . '%';
                        ?>
                    ";
            }
        });

        Blade::directive("fmt_date", function ($expression) {
            switch (config("app.locale")) {
                case "de":
                    return "
                        <?php
                            echo date('d.m.Y', $expression);
                        ?>
                    ";

                default:
                    return "
                        <?php
                            echo date('Y-m-d', $expression);
                        ?>
                    ";
            }
        });
    }
}
