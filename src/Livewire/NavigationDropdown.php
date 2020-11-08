<?php

namespace Aldrumo\Core\Livewire;

use Aldrumo\ThemeManager\ThemeManager;
use Laravel\Jetstream\Http\Livewire\NavigationDropdown as NavDropdownBase;

class NavigationDropdown extends NavDropdownBase
{
    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $activeTheme = resolve(ThemeManager::class)->activeTheme()->packageName();

        return view($activeTheme . '::navigation-dropdown');
    }
}
