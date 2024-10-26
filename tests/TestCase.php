<?php

namespace MichaelBecker\SimpleFile\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use MichaelBecker\SimpleFile\SimpleFileServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'MichaelBecker\\SimpleFile\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SimpleFileServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-simple-file-model_table.php.stub';
        $migration->up();
        */
    }
}
