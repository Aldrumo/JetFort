<?php

namespace Aldrumo\Core\Tests;

use Aldrumo\Core\Providers\AldrumoCoreServiceProvider;
use Aldrumo\Core\Tests\Fixtures\TestClasses\AnotherThemeServiceProvider;
use Aldrumo\Core\Tests\Fixtures\TestClasses\DefaultThemeServiceProvider;
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
        ];
    }
}
