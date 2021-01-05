<?php

namespace Aldrumo\Core\Http\Controllers;

use Aldrumo\Core\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PageController
{
    public function __invoke(Request $request)
    {
        $id = $this->findPageId($request);

        $page = Page::where('id', $id)
            ->where('is_active', 1)
            ->first();

        if ($page === null) {
            abort(404);
        }

        View::share([
            'inEditor' => false,
            'contentBlocks' => $page->blocks,
        ]);

        return view(
            $page->template,
            [
                'page' => $page,
            ]
        );
    }

    protected function findPageId(Request $request) : int
    {
        return str_replace('route-', '', $request->route()->getName());
    }
}
