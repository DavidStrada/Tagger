<?php

namespace DavidStrada\Tagger;

use DavidStrada\Tagger\Tagger;
use Illuminate\Support\ServiceProvider;

class TaggerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'davidstrada');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'davidstrada');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tagger.php', 'tagger');

        // Register the service the package provides.
        $this->app->singleton('tagger', function ($app) {
            return new Tagger;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['tagger'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/tagger.php' => config_path('tagger.php'),
        ], 'tagger.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/davidstrada'),
        ], 'tagger.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/davidstrada'),
        ], 'tagger.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/davidstrada'),
        ], 'tagger.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
