<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'parent' => null,
        'level' => 1,
        'name' => $faker->name,
        'comment' => $faker->text(200) ,
    ];
});
