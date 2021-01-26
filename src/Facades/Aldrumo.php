<?php

namespace Aldrumo\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string version()
 * @method static bool isInstalled()
 */
class Aldrumo extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'aldrumo';
    }
}
