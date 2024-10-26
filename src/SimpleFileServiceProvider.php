<?php

namespace MichaelBecker\SimpleFile;

use MichaelBecker\SimpleFile\Services\FileService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SimpleFileServiceProvider extends PackageServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton(FileService::class, function ($app) {
            return new FileService;
        });
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-simple-file-model')
            ->hasConfigFile()
            ->hasMigration('create_files_table')
            ->hasRoute('simple-file-model');

        // Publish the routes, controller, and policy
        $this->publishes([
            __DIR__.'/../routes/simple-file-model.php' => base_path('routes/simple-file-model.php'),
            __DIR__.'/../Http/Controllers/FileController.php' => app_path('Http/Controllers/FileController.php'),
            __DIR__.'/../Policies/FilePolicy.php' => app_path('Policies/FilePolicy.php'),
        ], 'simple-file-resources');
    }
}
