<?php

namespace Approval\Tests;

use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setup()
    {
        parent::setup();

        $this->app->setBasePath(__DIR__.'/../');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Approval\ApprovalServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        Schema::dropIfExists('special_users');
        Schema::create('special_users', function ($table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->timestamp('birth_dat');
            $table->timestamps();
        });
    }
}
