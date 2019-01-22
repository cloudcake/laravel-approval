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
        $this->registerMigrations();
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
