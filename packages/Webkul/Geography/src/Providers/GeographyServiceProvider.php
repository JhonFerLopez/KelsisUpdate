<?php

namespace Webkul\Geography\Providers;

use Illuminate\Support\ServiceProvider;
/*use Webkul\Geography\Models\DepartmentProxy;
use Webkul\Geography\Models\TownProxy;
use Webkul\Geography\Observers\DepartmentObserver;
use Webkul\Geography\Observers\TownObserver;
*/
class GeographyServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		
		$this->app->register(ModuleServiceProvider::class);
		$this->app->register(EventServiceProvider::class);

		$this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');		

		//$this->loadRoutesFrom(__DIR__ . '/../Http/admin-routes.php');

		//$this->app->register(EventServiceProvider::class);

		//DepartmentProxy::observe(DepartmentObserver::class);
		//TownProxy::observe(TownObserver::class);
	}

	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerConfig();
	}

	/**
	 * Register package config.
	 *
	 * @return void
	 */
	protected function registerConfig()
	{
		$this->mergeConfigFrom(
			dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
		);

	}
}
