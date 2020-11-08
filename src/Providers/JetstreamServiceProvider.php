<?php

namespace Aldrumo\Core\Providers;

use Aldrumo\Core\Actions\Jetstream\DeleteUser;
use Aldrumo\ThemeManager\ThemeManager;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

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
        $activeTheme = $this->themeManager()->activeTheme()->packageName();

        Fortify::viewPrefix($activeTheme . '::auth');
    }

    protected function themeManager()
    {
        return $this->app[ThemeManager::class];
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
