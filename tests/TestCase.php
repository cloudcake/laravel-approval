<?php

namespace Approval\Tests;

use Approval\Tests\Models\Admin;
use Approval\Tests\Models\Post;
use Approval\Tests\Models\User;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setup()
    {
        parent::setup();

        $this->app->setBasePath(__DIR__.'/../');

        $this->loadMigrationsFrom(__DIR__.'/../src/Migrations');

        $this->artisan('migrate');

        Schema::create('admins', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('posts', function ($table) {
            $table->increments('id');
            $table->string('title');
            $table->string('content');
            $table->timestamps();
        });

        Post::create(['title' => 'Hello World', 'content' => 'Whiskey Tango Foxtrot']);
        Admin::create(['name' => 'John Doe']);
        User::create(['name' => 'Jane Doe']);
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
    }
}
