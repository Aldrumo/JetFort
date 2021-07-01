<?php

namespace Aldrumo\Core\Models;

use Aldrumo\ThemeManager\Theme\ThemeBase;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getActive() : Theme
    {
        return static::where('is_active', 1)->first();
    }

    public function getThemeBase() : ThemeBase
    {
        return app('Theme:' . $this->name);
    }
}
