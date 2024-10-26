<?php

namespace MichaelBecker\SimpleFile;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MichaelBecker\SimpleFile\Commands\SimpleFileCommand;

class SimpleFileServiceProvider extends PackageServiceProvider
{
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
            ->hasViews()
            ->hasMigration('create_laravel_simple_file_model_table')
            ->hasCommand(SimpleFileCommand::class);
    }
}
