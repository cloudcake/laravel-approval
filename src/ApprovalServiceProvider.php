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
        $this->registerMigrations();
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
