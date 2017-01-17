<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => ['api', 'jwt.auth']]);

        /*
         * Authenticate the user's personal channel...
         */
        Broadcast::channel('orders', function ($user) {
            return true;
        });
    }
}
