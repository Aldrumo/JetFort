<?php

namespace Aldrumo\Core\Tests;

use Aldrumo\Core\Providers\AldrumoCoreServiceProvider;
use Aldrumo\Core\Tests\Fixtures\EmptyRouteLoader;
use Aldrumo\Core\Tests\Fixtures\TestClasses\AnotherThemeServiceProvider;
use Aldrumo\Core\Tests\Fixtures\TestClasses\DefaultThemeServiceProvider;
use Aldrumo\RouteLoader\Contracts\RouteLoader;
use Aldrumo\RouteLoader\RouteLoaderServiceProvider;
use Aldrumo\Settings\SettingsServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            DefaultThemeServiceProvider::class,
            AnotherThemeServiceProvider::class,
            LivewireServiceProvider::class,
            AldrumoCoreServiceProvider::class,
            RouteLoaderServiceProvider::class,
            SettingsServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set(
            'routeloader.controller',
            'PageController@load'
        );

        $app->bind(
            RouteLoader::class,
            EmptyRouteLoader::class
        );
    }

}
