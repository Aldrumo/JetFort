<?php

namespace Aldrumo\Core\Routes;

use Aldrumo\Core\Models\Page;
use Aldrumo\RouteLoader\Contracts\RouteLoader;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class Loader implements RouteLoader
{
    public function getRoutes(): Collection
    {
        try {
            return Page::where('is_active', true)->get();
        } catch (QueryException $e) {
            return collect([]);
        }
    }
}
