<?php

use Faker\Generator as Faker;

$factory->define(App\Ad::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'content' => $faker->text,
        'contact' => $faker->sentence,
        //
    ];
});
