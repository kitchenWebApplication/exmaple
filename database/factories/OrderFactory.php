<?php

$factory->define(App\Models\Order::class, function (Faker\Generator $faker) {
    return [
        'user_id'      => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'status_id'    => $faker->randomKey(App\Models\Order::statuses()),
        'cooking_time' => $faker->time('H:i'),
        'table_number' => $faker->numberBetween(1, 100),
        'dish_name'    => $faker->word
    ];
});
