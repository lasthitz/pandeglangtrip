<?php

namespace Modules\Admin\app\Providers;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register view namespace: admin::...
        $this->loadViewsFrom(module_path('Admin', 'resources/views'), 'admin');
    }
}
