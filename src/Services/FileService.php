<?php

namespace MichaelBecker\SimpleFile\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use MichaelBecker\SimpleFile\Models\File;

class FileService
{
    /**
     * Store files for a given model that uses the HasFiles trait.
     *
     * @param array|null $newFiles
     * @param \Illuminate\Database\Eloquent\Model $model Model with HasFiles trait
     * @param string|null $path
     * @return mixed
     * @throws \Exception if $model does not implement getDisk or lacks id property.
     */
    public function storeFiles(
        ?array $newFiles,
        Model $model,
        ?string $path = null
    ) {
        if (! method_exists($model, 'getDisk')) {
            throw new \Exception("The provided model does not implement the required getDisk method.");
        }
        $disk = $model->getDisk() ?? 'public';
        $path = $path ?? $model->id ?? '';

        $files = [];

        if (!empty($newFiles)) {
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
                        // @phpstan-ignore-next-line
                        'fileable_id' => $model->id,
                        'fileable_type' => get_class($model),
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
