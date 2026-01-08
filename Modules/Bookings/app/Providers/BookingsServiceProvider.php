<?php

namespace Modules\Bookings\app\Providers;

use Illuminate\Support\ServiceProvider;

class BookingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Register views namespace: bookings::*
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'bookings');
    }
}
