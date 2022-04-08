<?php

namespace Bizbezzie\Zohobooks;

use Illuminate\Support\ServiceProvider;

class ZohobooksServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'bizbezzie');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'bizbezzie');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
         $this->loadRoutesFrom(__DIR__.'/routes.php');

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
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/zohobooks.php', 'zohobooks');

        // Register the service the package provides.
        $this->app->singleton('zohobooks', function () {
            return new Zohobooks;
        });

        $this->app->singleton('zohobookshelper', function () {
            return new zohobooksHelper;
        });

        $this->app->singleton('contact', function () {
            return new Contact;
        });

        $this->app->singleton('invoice', function () {
            return new Invoice;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['zohobooks'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/zohobooks.php' => config_path('zohobooks.php'),
        ], 'zohobooks-config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/bizbezzie'),
        ], 'zohobooks.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/bizbezzie'),
        ], 'zohobooks.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/bizbezzie'),
        ], 'zohobooks.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
