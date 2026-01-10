<?php

namespace Modules\Tours\App\Providers;

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

        // ✅ Register view namespace: tours::
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'tours');
    }
}
