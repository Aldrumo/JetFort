<?php

namespace Aldrumo\Core\Providers;

use Aldrumo\Core\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Http\Livewire\NavigationDropdown;
use Laravel\Jetstream\Jetstream;
use Livewire\Livewire;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();
        $this->setJetstreamViews();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    protected function setJetstreamViews()
    {
        Fortify::viewPrefix('Admin::auth.');
    }

    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
