<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Property;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Property::class, function (Faker $faker) {
    return [
        'suburb' => $faker->city,
        'state' => $faker->state,
        'country' => $faker->country,
    ];
});
