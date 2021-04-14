<?php

namespace Aldrumo\Core;

use Composer\Semver\Comparator;

class Aldrumo
{
    public function version(): string
    {
        return '0.4.0';
    }

    public function currentVersion(): string
    {
        return trim(file_get_contents(base_path('aldrumo.installed')));
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
            trim(file_get_contents(base_path('aldrumo.installed')))
        );
    }
}
