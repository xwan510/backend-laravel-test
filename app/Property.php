<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Property extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'country' => 'Australia',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'suburb',
        'state',
        'country'
    ];

    /**
     * Automatically populates UUID on create.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            $property->guid = (string) Str::uuid();
        });
    }

    /**
     * The analytics that belong to the property.
     *
     * @return void
     */
    public function analytics()
    {
        return $this->belongsToMany('App\AnalyticType', 'property_analytics')
                        ->using('App\PropertyAnalytic')
                        ->withPivot([
                            'value',
                        ]);
    }
}
