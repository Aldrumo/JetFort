<?php

namespace Aldrumo\Core\Http\Controllers;

use Illuminate\Http\Request;

class PageController
{
    public function __invoke(Request $request)
    {
        dd($request);
    }
}
