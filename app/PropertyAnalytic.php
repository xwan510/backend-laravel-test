<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PropertyAnalytic extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
}
