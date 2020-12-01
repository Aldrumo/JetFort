<?php

namespace Aldrumo\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'template',
        'is_active',
    ];
}
