<?php

namespace Aldrumo\Core\Traits;

trait UpdateHelpers
{
    /**
     * Replace the given string in the given file.
     * @link https://github.com/laravel/installer/blob/master/src/NewCommand.php
     */
    protected function replaceInFile(string $search, string $replace, string $file)
    {
        file_put_contents(
            $file,
            str_replace($search, $replace, file_get_contents($file))
        );
    }
}
