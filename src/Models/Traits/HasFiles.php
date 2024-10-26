<?php

namespace MichaelBecker\SimpleFile\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use MichaelBecker\SimpleFile\Models\File;

trait HasFiles
{
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            if (in_array(SoftDeletes::class, class_uses_recursive($model))) {
                // If the model uses SoftDeletes, check if it is being soft-deleted or force-deleted
                if ($model->isForceDeleting()) {
                    // If force-deleting, permanently delete associated files
                    $model->files()->each(fn (File $file) => $file->forceDelete());
                } else {
                    // If soft-deleting, only soft-delete associated files
                    $model->files()->each(fn (File $file) => $file->delete());
                }
            } else {
                // If the model does not use SoftDeletes, permanently delete associated files
                $model->files()->each(fn (File $file) => $file->forceDelete());
            }
        });
    }

    public function getDisk()
    {
        return ! defined(static::class.'::DISK') ? 'public' : self::DISK;
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function hasFiles(): bool
    {
        return $this->files->isNotEmpty();
    }

    public function getFileName()
    {
        return $this->files?->first()?->name ?? '';
    }

    public function getPdfFiles()
    {
        if (! $this->hasFiles()) {
            return collect();
        }

        return $this->files->filter(fn ($file) => Str::contains(Str::lower($file->name), '.pdf'));
    }

    public function getImages()
    {
        if (! $this->hasFiles()) {
            return collect();
        }

        return $this->files->filter(fn ($file) => in_array(pathinfo($file->name, PATHINFO_EXTENSION), config('simple-file-model.image_extensions')));
    }
}
