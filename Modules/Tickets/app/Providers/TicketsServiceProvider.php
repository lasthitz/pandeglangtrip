<?php

namespace Modules\Tickets\app\Providers;

use Illuminate\Support\ServiceProvider;

class TicketsServiceProvider extends ServiceProvider
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
