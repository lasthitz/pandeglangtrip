<?php

namespace Modules\Admin\app\Providers;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
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
