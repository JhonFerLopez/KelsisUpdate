<?php

namespace Webkul\Preparation\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Preparation\Models\PreparationProxy;
use Webkul\Preparation\Observers\PreparationObserver;

class PreparationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->app->register(EventServiceProvider::class);

        PreparationProxy::observe(PreparationObserver::class);
    }
}
