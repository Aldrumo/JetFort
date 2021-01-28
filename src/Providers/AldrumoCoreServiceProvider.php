<?php

namespace Aldrumo\Core\Providers;

use Aldrumo\Admin\Contracts\AdminManager;
use Aldrumo\Admin\Manager\MenuItem;
use Aldrumo\Core\Aldrumo;
use Aldrumo\Core\Routes\Loader;
use Aldrumo\Installer\Console\Commands\AldrumoInstall;
use Aldrumo\RouteLoader\Contracts\RouteLoader;
use Aldrumo\RouteLoader\Generator;
use Aldrumo\Settings\Contracts\Repository as SettingsContract;
use Aldrumo\ThemeManager\ThemeManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AldrumoCoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerBindings();
        $this->registerConfigs();
        $this->registerRouteLoader();
        $this->registerServerProviders();
    }

    public function boot()
    {
        $this->bootCommands();
        $this->bootMigrations();
        $this->bootPublishes();

        if ($this->app['aldrumo']->isInstalled()) {
            $this->bootRoutes();
            $this->bootTheme();
        }
    }

    protected function bootCommands()
    {
        if ($this->app->runningInConsole() && ! Aldrumo::isInstalled()) {
            $this->commands([
                AldrumoInstall::class,
            ]);
        }
    }

    protected function bootMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    protected function bootPublishes()
    {
        $this->publishes([
            __DIR__ . '/../../config/sanctum.php'   => config_path('sanctum.php'),
            __DIR__ . '/../../config/fortify.php'   => config_path('fortify.php'),
            __DIR__ . '/../../config/jetstream.php' => config_path('jetstream.php'),
        ]);
    }

    protected function bootRoutes()
    {
        Route::middleware('web')
            ->group(
                function () {
                    $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
                }
            );

        Route::middleware('web')
            ->group(
                function () {
                    resolve(Generator::class)->generateRoutes();
                }
            );

        Route::middleware('api')
            ->group(
                function () {
                    $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
                }
            );
    }

    protected function bootTheme()
    {
        resolve(ThemeManager::class)->activeTheme(
            resolve(SettingsContract::class)->get('activeTheme')
        );
    }

    protected function registerBindings()
    {
        $this->app->singleton(
            'aldrumo',
            function () {
                return new Aldrumo();
            }
        );
    }

    protected function registerConfigs()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/sanctum.php',
            'sanctum'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/fortify.php',
            'fortify'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/jetstream.php',
            'jetstream'
        );
    }

    protected function registerRouteLoader()
    {
        $this->app->bind(
            RouteLoader::class,
            Loader::class
        );
    }

    protected function registerServerProviders()
    {
        $this->app->booting(function ($app) {
            $app->register(FortifyServiceProvider::class);
            $app->register(JetstreamServiceProvider::class);
        });
    }
}
