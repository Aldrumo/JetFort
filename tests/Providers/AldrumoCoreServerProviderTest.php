<?php

namespace Aldrumo\Core\Tests\Providers;

use Aldrumo\Core\Providers\FortifyServiceProvider;
use Aldrumo\Core\Providers\JetstreamServiceProvider;
use Aldrumo\Core\Tests\TestCase;

class AldrumoCoreServerProviderTest extends TestCase
{
    /** @test */
    public function test_fortify_jetstream_get_booted()
    {
        $this->assertTrue(
            $this->app->providerIsLoaded(FortifyServiceProvider::class)
        );

        $this->assertTrue(
            $this->app->providerIsLoaded(JetstreamServiceProvider::class)
        );
    }
}
