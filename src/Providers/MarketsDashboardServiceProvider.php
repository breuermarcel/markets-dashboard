<?php

namespace Breuermarcel\MarketsDashboard\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class MarketsDashboardServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'markets-dashboard');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'markets-dashboard');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadBladeDirectives();
        $this->registerRoutes();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/markets-dashboard.php', 'markets-dashboard');
    }

    private function routeConfiguration(): array
    {
        return [
            'prefix' => config('markets-dashboard.routing.prefix'),
            'as' => config('markets-dashboard.routing.as'),
            'middleware' => config('markets-dashboard.routing.middleware'),
        ];
    }

    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes.php');
        });
    }

    private function loadBladeDirectives()
    {
        Blade::directive('fmt_money', function ($expression) {
            return match (config('app.locale')) {
                'de' => "
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

        Blade::directive('fmt_number', function ($expression) {
            return match (config('app.locale')) {
                'de' => "
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

        Blade::directive('fmt_decimal', function ($expression) {
            return match (config('app.locale')) {
                'de' => "
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

        Blade::directive('fmt_percentage', function ($expression) {
            return match (config('app.locale')) {
                'de' => "
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

        Blade::directive('fmt_date', function ($expression) {
            return match (config('app.locale')) {
                'de' => "
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
