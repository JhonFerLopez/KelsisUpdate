<?php

namespace Webkul\Kelsis\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Kelsis\Facades\Velocity as VelocityFacade;

class KelsisServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/helpers.php';

        Route::middleware('web')->group(__DIR__ . '/../Routes/admin-routes.php');
        Route::middleware('web')->group(__DIR__ . '/../Routes/front-routes.php');

        $this->app->register(EventKelsisServiceProvider::class);

        $this->loadGlobalVariables();

        $this->loadPublishableAssets();

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'kelsis');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'kelsis');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerFacades();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php',
            'acl'
        );
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('velocity', VelocityFacade::class);
    }

    /**
     * This method will load all publishables.
     *
     * @return boolean
     */
    private function loadPublishableAssets()
    {
        $this->publishes([
            __DIR__ . '/../../publishable/assets/' => public_path('themes/kelsis/assets'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../Resources/views/shop' => resource_path('themes/kelsis/views'),
        ]);

        $this->publishes([__DIR__ . '/../Resources/lang' => lang_path('vendor/velocity')]);

        return true;
    }

    /**
     * This method will provide global variables shared by view (blade files).
     *
     * @return boolean
     */
    private function loadGlobalVariables()
    {
        view()->composer('*', function ($view) {
            $velocityHelper = app(\Webkul\Velocity\Helpers\Helper::class);

            $view->with('showRecentlyViewed', true);
            $view->with('velocityHelper', $velocityHelper);
            $view->with('velocityMetaData', $velocityHelper->getVelocityMetaData());
        });

        return true;
    }
}
