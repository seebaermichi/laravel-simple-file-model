<?php

namespace MichaelBecker\SimpleFile\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use MichaelBecker\SimpleFile\Models\File;

class FileService
{
    /**
     * Store files for a given model.
     *
     * @param array|null $newFiles
     * @param Model|null $model
     * @param string|null $path
     * @return mixed
     */
    public function storeFiles(
        ?array $newFiles,
        Model $model,
        ?string $path = null
    ) {
        if (is_null($model)) {
            throw new Exception(__('A model must be provided to associate files.'));
        }

        $files = [];

        if (!empty($newFiles)) {
            // Determine the disk from the model or default to 'public'
            $disk = $model?->getDisk() ?? 'public';
            $path = $path ?? $model?->id ?? '';

            foreach ($newFiles as $newFile) {
                // Check if the file already exists
                if (!Storage::disk($disk)->exists($path . '/' . $newFile->getClientOriginalName())) {
                    $newFile->storeAs($path, $newFile->getClientOriginalName(), $disk);

                    // Create a File model entry
                    $files[] = File::create([
                        'disk' => $disk,
                        'path' => $path,
                        'name' => $newFile->getClientOriginalName(),
                        'uploaded_by' => auth()->id(),
                        'fileable_id' => $model?->id,
                        'fileable_type' => $model ? get_class($model) : null,
                    ])->id;
                } else {
                    // If file exists, retrieve its record
                    $files[] = File::where('disk', $disk)
                        ->where('path', $path)
                        ->where('name', $newFile->getClientOriginalName())
                        ->first()->id;
                }
            }

            return count($files) === 1 ? $files[0] : $files;
        }
    }
}
