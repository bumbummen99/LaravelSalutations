<?php

namespace SkyRaptor\LaravelSalutations;

use Illuminate\Support\ServiceProvider;

class SalutationsServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('salutations', 'SkyRaptor\LaravelSalutations\Salutations');
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/Translations', 'salutations');

        $this->publishes([
            __DIR__.'/Config/salutations.php' => config_path('salutations.php'),
        ]);
    }
}