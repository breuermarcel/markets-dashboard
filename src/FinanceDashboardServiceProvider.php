<?php

namespace Breuermarcel\FinanceDashboard;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
            return new Chat;
        });
    }

    private function routeConfiguration(): array
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
            return match (config("app.locale")) {
                "de" => "
                        <?php
                            if ($expression > 100)
                                echo number_format($expression, 0, ',', '.') . '€';
                            else
                                echo number_format($expression, 2, ',', '.') . '€';
                        ?>
                    ",
                default => "
                        <?php
                            if ($expression > 100)
                                echo '$' . number_format($expression, 0);
                            else
                                echo '$' . number_format($expression, 2);
                        ?>
                    ",
            };
        });

        Blade::directive("fmt_number", function ($expression) {
            return match (config("app.locale")) {
                "de" => "
                        <?php
                            echo number_format($expression, 0, ',', '.');
                        ?>
                    ",
                default => "
                        <?php
                            echo number_format($expression, 0);
                        ?>
                    ",
            };
        });

        Blade::directive("fmt_decimal", function ($expression) {
            return match (config("app.locale")) {
                "de" => "
                        <?php
                            echo number_format($expression, 2, ',', '.');
                        ?>
                    ",
                default => "
                        <?php
                            echo number_format($expression, 2);
                        ?>
                    ",
            };
        });

        Blade::directive("fmt_percentage", function ($expression) {
            return match (config("app.locale")) {
                "de" => "
                        <?php
                            echo number_format($expression, 2, ',', '.') * 100 . '%';
                        ?>
                    ",
                default => "
                        <?php
                            echo number_format($expression, 2) * 100 . '%';
                        ?>
                    ",
            };
        });

        Blade::directive("fmt_date", function ($expression) {
            return match (config("app.locale")) {
                "de" => "
                        <?php
                            echo date('d.m.Y', $expression);
                        ?>
                    ",
                default => "
                        <?php
                            echo date('Y-m-d', $expression);
                        ?>
                    ",
            };
        });
    }
}
