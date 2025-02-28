<?php

namespace TotyaDev\TotyaDevMediaManager;

use Illuminate\Support\ServiceProvider;
use TotyaDev\TotyaDevMediaManager\Resources\FolderResource\Widgets\FolderWidget;
use TotyaDev\TotyaDevMediaManager\Services\TotyaDevMediaManagerServices;


class TotyaDevMediaManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
            \TotyaDev\TotyaDevMediaManager\Console\TotyaDevMediaManagerInstall::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__ . '/../config/totyadev-media-manager.php', 'totyadev-media-manager');

        //Publish Config
        $this->publishes([
            __DIR__ . '/../config/totyadev-media-manager.php' => config_path('totyadev-media-manager.php'),
        ], 'totyadev-media-manager-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        //Publish Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'totyadev-media-manager-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'totyadev-media-manager');

        //Publish Views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/totyadev-media-manager'),
        ], 'totyadev-media-manager-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'totyadev-media-manager');

        //Publish Lang
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('lang/vendor/totyadev-media-manager'),
        ], 'totyadev-media-manager-lang');

        if (config('totyadev-media-manager.api.active')) {
            //Register Routes
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        }

        $this->app->bind('totyadev-media-manager', function () {
            return new TotyaDevMediaManagerServices();
        });
    }

    public function boot(): void
    {
        //you boot methods here
    }
}
