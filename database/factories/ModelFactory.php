<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
// use Faker\Factory as Faker;


$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});



$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->jobTitle,
    ];
});


$factory->define(App\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'created_at'=>$faker->dateTimeBetween($startDate = '-30 days', $endDate = '-15 days'),
        'updated_at'=>$faker->dateTimeBetween($startDate = '-14 days'),
    ];
});




$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->title,
        'body' => $faker->paragraph(2),
        'user_id' => $faker->numberBetween(1,10),
        'category_id' => $faker->numberBetween(1,10),
        'created_at'=>$faker->dateTimeBetween($startDate = '-30 days', $endDate = '-15 days'),
        'updated_at'=>$faker->dateTimeBetween($startDate = '-14 days'),
    ];
});
