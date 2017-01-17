<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Model's namespace.
     *
     * @var string
     */
    protected $modelNamespace = 'App\Models';

    /**
     * Observer's namespace.
     *
     * @var string
     */
    protected $observerNamespace = 'App\Observers';

    /**
     * List of the observers with associating with the models.
     *
     * @var array
     */
    protected $observers = [
        'Order' => 'OrderObserver',
        'User'  => 'UserObserver'
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerObservers();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register the model observers.
     *
     * @return void
     */
    protected function registerObservers()
    {
        foreach ($this->observers as $model => $observer) {
            call_user_func(
                $this->modelNamespace . '\\' . $model . '::observe',
                $this->observerNamespace . '\\' . $observer
            );
        }
    }
}
