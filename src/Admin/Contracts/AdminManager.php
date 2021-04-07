<?php

namespace Aldrumo\Core\Admin\Contracts;

use Illuminate\Support\Collection;

interface AdminManager
{
    public function menu(): Collection;
}
