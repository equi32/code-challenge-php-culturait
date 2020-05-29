<?php

use Faker\Generator as Faker;
use App\Note;

$factory->define(Note::class, function (Faker $faker) {
    return [
    	'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
    	'description' => $faker->text($maxNbChars = 200),
        'category' => $faker->randomElement(config('categories')),
        'status' => $faker->randomElement([0, 1]),
        'tags' => implode("|", $faker->words($nb = 5, $asText = false)),
        'user_id' => 1
    ];
});
