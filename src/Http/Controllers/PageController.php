<?php

namespace Aldrumo\Core\Http\Controllers;

use Aldrumo\Core\Models\Page;
use Aldrumo\ThemeManager\ThemeManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PageController
{
    /** @var ThemeManager */
    protected $themeManager;

    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

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
            $this->themeManager->activeTheme()->packageName() . '::' .
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
