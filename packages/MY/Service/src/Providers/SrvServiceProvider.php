<?php


namespace MY\Service\Providers;


use Illuminate\Support\ServiceProvider;
use MY\Service\Models\ServiceProxy;
use MY\Service\Observers\ServiceObserver;

class SrvServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(){
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'service');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'service');

//        ServiceProxy::observe(ServiceObserver::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );
    }
}