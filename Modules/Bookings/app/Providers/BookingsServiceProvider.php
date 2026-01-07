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
    $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
}

}
