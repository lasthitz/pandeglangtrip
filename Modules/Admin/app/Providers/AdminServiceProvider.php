<?php

namespace Modules\Admin\App\Providers;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register view namespace: admin::...
        $this->loadViewsFrom(module_path('Admin', 'resources/views'), 'admin');
    }
}
