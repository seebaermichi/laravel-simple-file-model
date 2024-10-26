<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MichaelBecker\SimpleFile\Models\File;
use MichaelBecker\SimpleFile\Services\FileService;
use MichaelBecker\SimpleFile\Tests\TestModel;

it('can store a new file and associate it with a model', function () {
    Storage::fake('public');
    $fileService = new FileService;
    $testModel = TestModel::factory()->create();

    $uploadedFile = UploadedFile::fake()->create('example.pdf', 100);

    $fileId = $fileService->storeFiles([$uploadedFile], $testModel);

    expect($fileId)->not->toBeNull();
    expect(File::find($fileId))->toBeInstanceOf(File::class);
    expect(File::find($fileId)->fileable_id)->toBe($testModel->id);
});

it('returns the existing file ID if a file with the same name already exists', function () {
    Storage::fake('public');
    $fileService = new FileService;

    $uploadedFile = UploadedFile::fake()->create('example.pdf', 100);
    $uploadedFile->storeAs('folder', 'example.pdf', 'public');
    $model = TestModel::factory()->create();

    $existingFile = File::factory()->create([
        'disk' => 'public',
        'path' => 'folder',
        'name' => 'example.pdf',
        'fileable_id' => $model->id,
        'fileable_type' => get_class($model),
    ]);

    $fileId = $fileService->storeFiles([$uploadedFile], $model, 'folder');

    expect($fileId)->toBe($existingFile->id);
});

it('stores multiple files and returns an array of file IDs', function () {
    Storage::fake('public');
    $testModel = TestModel::factory()->create();
    $fileService = new FileService;

    $files = [
        UploadedFile::fake()->create('file1.pdf', 100),
        UploadedFile::fake()->create('file2.pdf', 100),
    ];

    $fileIds = $fileService->storeFiles($files, $testModel);

    expect($fileIds)->toBeArray();
    expect(count($fileIds))->toBe(2);
});
