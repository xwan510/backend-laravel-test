<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PropertyAnalytic extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'property_analytics';

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
