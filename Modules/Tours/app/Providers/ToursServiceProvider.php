<?php

namespace Modules\Tours\app\Providers;

use Illuminate\Support\ServiceProvider;

class ToursServiceProvider extends ServiceProvider
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
