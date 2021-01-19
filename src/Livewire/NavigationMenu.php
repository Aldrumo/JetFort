<?php

namespace Aldrumo\Core\Livewire;

use Laravel\Jetstream\Http\Livewire\NavigationMenu as NavMenuBase;

class NavigationMenu extends NavMenuBase
{
    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('Admin::navigation-menu');
    }
}
