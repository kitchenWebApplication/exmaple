<?php

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'role'               => $faker->randomKey(App\Models\User::roles()),
        'email'              => $faker->unique()->safeEmail,
        'password'           => $password ?: $password = bcrypt('secret'),
        'first_name'         => $faker->firstName,
        'last_name'          => $faker->lastName,
        'is_active'          => $faker->boolean,
        'remember_token'     => str_random(10),
        'confirmation_token' => str_random(10),
    ];
});

$factory->state(App\Models\User::class, 'manager', function () {
    return [
        'role' => App\Models\User::ROLE_MANAGER
    ];
});

$factory->state(App\Models\User::class, 'waiter', function () {
    return [
        'role' => App\Models\User::ROLE_WAITER
    ];
});
