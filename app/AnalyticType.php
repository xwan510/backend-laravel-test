<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalyticType extends Model
{

    /**
     * The properties that belong to the analytic type.
     *
     */
    public function properties()
    {
        return $this->belongsToMany('App\Property', 'property_analytics')
                        ->using('App\PropertyAnalytic')
                        ->as('analytic')
                        ->withTimestamps()
                        ->withPivot([
                            'value',
                        ]);
    }
}
