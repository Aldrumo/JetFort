<?php

class Update_030
{
    use \Aldrumo\Core\Traits\UpdateHelpers;

    public function handle()
    {
        \Illuminate\Support\Facades\Artisan::call(
            'vendor:publish',
            [
                '--provider' => 'Aldrumo\\ThemeManager\\ThemeManagerServerProvider'
            ]
        );

        $this->replaceInFile(
            "'activeTheme' => null,",
            "'activeTheme' => 'AldrumoCore::defaults.no-active-theme',",
            config_path('theme-manager.php')
        );

        $this->replaceInFile(
            "'themeNotFound' => null,",
            "'themeNotFound' => 'AldrumoCore::defaults.theme-404',",
            config_path('theme-manager.php')
        );
    }
}
