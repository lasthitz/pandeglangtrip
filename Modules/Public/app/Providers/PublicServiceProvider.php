<?php

namespace Modules\Public\app\Providers;

use Illuminate\Support\ServiceProvider;

class PublicServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('Public', 'routes/web.php'));

        $this->loadViewsFrom(
            module_path('Public', 'resources/views'),
            'public'
        );
    }
}
