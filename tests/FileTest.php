<?php

use Illuminate\Support\Facades\Storage;
use MichaelBecker\SimpleFile\Models\File;
use MichaelBecker\SimpleFile\Tests\TestModel;

it('can return full path of file', function () {
    $fileName = 'test.' . config('simple-file-model.image_extensions')[0];

    $fileModel = File::factory()->create([
        'name' => $fileName,
        'path' => 'folder',
    ]);

    expect($fileModel->getFullPath())->toBe('folder/' . $fileName);
});

it('can check if a file is an image by file extension', function () {
    $fileModel = File::factory()->create([
        'name' => 'test.' . config('simple-file-model.image_extensions')[0],
    ]);

    expect($fileModel->isImage())->toBe(true);
});

it('can check if a file is a pdf file by file extension', function () {
    $fileModel = File::factory()->create([
        'name' => 'test.pdf',
    ]);

    expect($fileModel->isPdf())->toBe(true);
});

it('can check if a file can be previewed by file extension', function () {
    $fileModel = File::factory()->create([
        'name' => 'test.pdf',
    ]);

    expect($fileModel->isPreviewable())->toBe(true);
});

it('can check if a file can not be previewed by file extension', function () {
    $fileModel = File::factory()->create([
        'name' => 'test.txt',
    ]);

    expect($fileModel->isPreviewable())->toBe(false);
});

it('soft deletes associated files when the model is soft deleted', function () {
    Storage::fake('public');
    $model = TestModel::factory()->create();

    $file = File::factory()->create([
        'name' => 'test.txt',
        'path' => 'folder',
        'disk' => 'public',
        'fileable_id' => $model->id,
        'fileable_type' => get_class($model),
    ]);

    Storage::disk('public')->put($file->getFullPath(), 'Sample content');

    // Soft delete the model, which should soft-delete the associated file
    $model->delete();

    // Check if the file is soft-deleted (exists in the database but is trashed)
    $softDeletedFile = File::withTrashed()->find($file->id);
    expect($softDeletedFile)->not->toBeNull();
    expect($softDeletedFile?->trashed())->toBeTrue();

    // Verify the physical file still exists on disk
    Storage::disk('public')->assertExists($file->getFullPath());
});



it('deletes associated files when the model is deleted', function () {
    Storage::fake('public');
    $model = TestModel::factory()->create();

    $file = File::factory()->create([
        'name' => 'test.txt',
        'path' => 'folder',
        'disk' => 'public',
        'fileable_id' => $model->id,
        'fileable_type' => get_class($model),
    ]);

    Storage::disk('public')->put($file->getFullPath(), 'Sample content');

    // Soft delete the model, which should soft-delete the associated file
    $model->forceDelete();

    // Check if the file is soft-deleted (exists in the database but is trashed)
    $deletedFile = File::withTrashed()->find($file->id);
    expect($deletedFile)->toBeNull();

    // Verify the physical file still exists on disk
    Storage::disk('public')->assertMissing($file->getFullPath());
});
