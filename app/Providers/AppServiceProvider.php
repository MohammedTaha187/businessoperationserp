<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        \Illuminate\Database\Eloquent\Factories\Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\V1\\'.class_basename($modelName).'Factory';
        });

        // Register Business Logic Observers
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        \App\Models\Invoice::observe(\App\Observers\InvoiceObserver::class);
    }
}
