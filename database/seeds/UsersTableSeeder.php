<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        factory(App\Models\User::class)->states('manager')->create([
            'email'     => 'manager@kitchen.dev',
            'password'  => bcrypt('manager'),
            'is_active' => true
        ]);

        factory(App\Models\User::class)->states('waiter')->create([
            'email'     => 'waiter@kitchen.dev',
            'password'  => bcrypt('waiter'),
            'is_active' => true
        ]);

        factory(App\Models\User::class, 5)->states('waiter')->create();
    }
}
