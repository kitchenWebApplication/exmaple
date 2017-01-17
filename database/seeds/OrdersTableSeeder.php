<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->truncate();

        factory(App\Models\Order::class, 12)->create([
            'user_id' => function () {
                return App\Models\User::query()
                    ->byRoleName('waiter')
                    ->inRandomOrder()
                    ->first()
                    ->id;
            }
        ]);
    }
}
