<?php

namespace Approval;

use Illuminate\Support\ServiceProvider;

class ApprovalServiceProvider extends ServiceProvider
{
    /**
     * Boot up Approval.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfigurations();
        $this->registerMigrations();
    }

    /**
     * Register Approval configs.
     *
     * @return void
     */
    private function registerConfigurations()
    {
        $this->publishes([
            __DIR__.'/Config/config.php' => config_path('approval.php'),
        ], 'config');
    }

    /**
     * Register Approval migrations.
     *
     * @return void
     */
    private function registerMigrations()
    {
        $this->publishes([
            __DIR__.'/Migrations' => database_path('migrations'),
        ], 'migrations');

        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }
}
