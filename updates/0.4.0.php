<?php

class Update_040
{
    use \Aldrumo\Core\Traits\UpdateHelpers;

    public function handle()
    {
        file_put_contents(
            config_path('themes.php'),
            file_get_contents(__DIR__ . '/../config/themes.php')
        );
    }
}
