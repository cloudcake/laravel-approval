<?php

namespace Approval;

use Illuminate\Support\ServiceProvider;

class ApprovalServiceProvider extends ServiceProvider
{
    /**
     * Boot up Shovel.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfigurations();
        $this->registerMigrations();
    }

    /**
     * Register Properties configs.
     *
     * @return void
     */
    private function registerConfigurations()
    {
        $this->publishes([
            __DIR__.'/Config/approval.php' => config_path('approval.php'),
        ]);
    }

    /**
     * Register Properties migrations.
     *
     * @return void
     */
    private function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }
}
