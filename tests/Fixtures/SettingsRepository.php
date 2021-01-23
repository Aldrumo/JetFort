<?php

namespace Aldrumo\Core\Tests\Fixtures;

use Aldrumo\Settings\Contracts\Repository as SettingsContract;

class SettingsRepository implements SettingsContract
{
    public function get(string $slug)
    {
        if ($slug === 'activeTheme') {
            return 'DefaultTheme';
        }

        return null;
    }

    public function set(string $slug, $data)
    {
        //
    }
}
