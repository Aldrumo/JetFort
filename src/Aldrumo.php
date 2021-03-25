<?php

namespace Aldrumo\Core;

use Composer\Semver\Comparator;

class Aldrumo
{
    public function version(): string
    {
        return '0.2.0';
    }

    public function isInstalled(): bool
    {
        return file_exists(base_path('aldrumo.installed'));
    }

    public function hasBeenUpdated(): bool
    {
        if (! $this->isInstalled()) {
            return false;
        }

        return Comparator::equalTo(
            $this->version(),
            file_get_contents(base_path('aldrumo.installed'))
        );
    }
}
