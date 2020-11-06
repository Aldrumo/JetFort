<?php

namespace Aldrumo\Core\Providers;

use Aldrumo\ThemeManager\ThemeManager;
use Illuminate\Support\ServiceProvider;

class AldrumoCoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->booting(function ($app) {
            $app->register(FortifyServiceProvider::class);
            $app->register(JetstreamServiceProvider::class);
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/fortify.php',
            'fortify'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/jetstream.php',
            'jetstream'
        );
    }

    public function boot()
    {
        resolve(ThemeManager::class)->activeTheme('DefaultTheme');

        $this->publishes([
            __DIR__ . '/../../config/fortify.php'   => config_path('fortify.php'),
            __DIR__ . '/../../config/jetstream.php' => config_path('jetstream.php'),
        ]);
    }
}
