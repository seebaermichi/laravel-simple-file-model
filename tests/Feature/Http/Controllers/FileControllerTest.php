<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MichaelBecker\SimpleFile\Models\File;
use MichaelBecker\SimpleFile\Tests\TestModel;

it('it has a route to show a file', function () {
    Storage::fake('public');

    $file = File::factory()->create([
        'name' => 'test.txt',
        'path' => 'folder',
    ]);

    Storage::disk('public')->put($file->getFullPath(), 'Sample content');

    $this->get(route('file.show', ['disk' => 'public', 'folder' => 'folder', 'name' => 'test.txt']))
        ->assertOk();
});

it('soft deletes the file record when the model is deleted', function () {
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

    // Soft delete the main model, which soft-deletes the associated file
    $model->delete();

    // Verify the file is soft-deleted in the database
    $softDeletedFile = File::withTrashed()->find($file->id);
    expect($softDeletedFile)->not->toBeNull();
    expect($softDeletedFile?->trashed())->toBeTrue();

    // Ensure the physical file still exists on the disk
    Storage::disk('public')->assertExists($file->getFullPath());
});

it('removes the physical file from disk when the file model is permanently deleted', function () {
    Storage::fake('public');

    $file = File::factory()->create([
        'name' => 'test.txt',
        'path' => 'folder',
        'disk' => 'public',
    ]);

    Storage::disk('public')->put($file->getFullPath(), 'Sample content');

    // Permanently delete the file model
    $file->forceDelete();

    // Verify the physical file is deleted from the storage disk
    Storage::disk('public')->assertMissing($file->getFullPath());
});
