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
    $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
}

}
