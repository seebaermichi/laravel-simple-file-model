# Laravel Simple File Model
`SimpleFileModel` is a lightweight Laravel package for managing file uploads and associations with Eloquent models. Designed to be flexible and easy to integrate, it provides a straightforward way to handle file storage, retrieval, and access control for various models. With customizable routes, policies, and a robust file service, SimpleFile offers developers a solid foundation for file management without the overhead.

__Features__
- _Easy Model Associations_: Attach files directly to models using the HasFiles trait.
- _Flexible File Storage_: Define custom storage disks for each model and configure paths effortlessly.
- _Access Control_: Include a customizable policy for managing file access.
- _Service-Based File Handling_: A FileService for storing and managing file uploads consistently across your application.
- _Publishable Resources_: Easily publish and customize routes, controllers, and policies to fit specific requirements.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/seebaermichi/laravel-simple-file-model.svg?style=flat-square)](https://packagist.org/packages/seebaermichi/laravel-simple-file-model)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/seebaermichi/laravel-simple-file-model/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/seebaermichi/laravel-simple-file-model/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/seebaermichi/laravel-simple-file-model/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/seebaermichi/laravel-simple-file-model/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/seebaermichi/laravel-simple-file-model.svg?style=flat-square)](https://packagist.org/packages/seebaermichi/laravel-simple-file-model)

## Installation
1. Require the Package

Add this package to your Laravel project via Composer:

```bash
composer require michaelbecker/simple-file
```

2. Publish Configuration and Resources

Publish the package configuration, routes, controller, and policy to customize them as needed:

```bash
php artisan vendor:publish --tag=simple-file-resources
```
This will publish the following files:

- _Configuration_: config/files.php – Defines options like admin roles and the user model.
- _Routes_: routes/simple-file-model.php – Routes for file operations.
- _Controller_: app/Http/Controllers/FileController.php – File handling methods.
- _Policy_: app/Policies/FilePolicy.php – File access control logic.

This is the contents of the published config file:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | User has Uuid
    |--------------------------------------------------------------------------
    |
    | This option defines whether the application uses UUIDs instead of integer
    | IDs for user model. Set to true if the User model use UUIDs.
    |
    */
    'user_has_uuid' => true,

    /*
    |--------------------------------------------------------------------------
    | Supported Image Extensions
    |--------------------------------------------------------------------------
    |
    | This array defines the file extensions that are considered as images in
    | the application. These extensions are used to identify and handle
    | image files for preview and validation purposes.
    |
    */
    'image_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'],
];
```

## Usage
Using the HasFiles Trait

Add the HasFiles trait to any Eloquent model that should manage files. This trait provides relationships and helper methods for associating files with the model.

```php
use MichaelBecker\SimpleFile\Traits\HasFiles;

class Client extends Model
{
    use HasFiles;
}
```
Working with File Attachments

Use the files() relationship to associate files with a model instance. You can retrieve, add, or delete files related to the model:

```php
$client = Client::find(1);

// Access files
$files = $client->files;

// Add a file
$client->files()->create([
    'disk' => 'public',
    'path' => 'uploads',
    'name' => 'example.jpg',
]);
```

## Usage Requirements

When using the HasFiles trait on a model:

Define a DISK constant if the model should use a specific storage disk other than public. For example:

```php
class Client extends Model
{
    use HasFiles;
    const DISK = 'clients';
}
```

Ensure any specified disk is configured in config/filesystems.php:
```php
'clients' => [
    'driver' => 'local',
    'root' => storage_path('app/clients'),
    'throw' => false,
],
```
Note: If no DISK constant is defined, the public disk will be used by default.

## Using the FileService to Store Files
You can use the FileService to manage file uploads and associations with models.  
Inject FileService into any controller or service where you want to use it:

```php
use MichaelBecker\SimpleFile\Services\FileService;

class YourController extends Controller
{
protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function store(Request $request)
    {
        $files = $this->fileService->storeFiles($request->file('attachments'), $yourModelInstance);
        // Process stored file IDs
    }
}
```
__Parameters:__
- `$newFiles`: Array of files to be uploaded.
- `$model`: (Optional) Model to associate files with.
- `$path`: (Optional) Path to store files within the disk.  

__Returns:__
- Returns a single file ID if only one file is uploaded, or an array of file IDs if multiple files are stored.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Michael Becker](https://github.com/seebaermichi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
