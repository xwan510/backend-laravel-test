<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AnalyticType;
use Faker\Generator as Faker;

$factory->define(AnalyticType::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'units' => $faker->word,
        'is_numeric' => true,
        'num_decimal_places' => $faker->randomDigit,
    ];
});
