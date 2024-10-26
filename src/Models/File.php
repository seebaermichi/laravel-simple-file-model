<?php

namespace MichaelBecker\SimpleFile\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $name
 * @property string $disk
 * @property string $path
 * @property int $fileable_id
 * @property string $fileable_type
 */
class File extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'disk',
        'path',
        'name',
        'deletable',
        'uploaded_by',
        'deleted_at',
        'fileable_id',
        'fileable_type',
    ];

    protected $casts = [
        'deletable' => 'bool',
    ];

    public static function boot()
    {
        parent::boot();

        static::forceDeleted(function ($file) {
            Storage::disk($file->disk)->delete($file->getFullPath());
        });
    }

    public function fileable()
    {
        return $this->morphTo();
    }

    public function getFullPath(): string
    {
        return $this->path . '/' . $this->name;
    }

    public function isPdf(): bool
    {
        return strtolower(pathinfo($this->name, PATHINFO_EXTENSION)) === 'pdf';
    }

    public function isImage(): bool
    {
        $extension = strtolower(pathinfo($this->name, PATHINFO_EXTENSION));

        return in_array($extension, config('simple-file-model.image_extensions'));
    }

    public function isPreviewable(): bool
    {
        return $this->isPdf() || $this->isImage();
    }

    public function routeData(): array
    {
        return [
            'disk' => $this->disk,
            'path' => $this->path,
        ];
    }

    public function getRoute(): string
    {
        return route('file.show', $this->routeData());
    }
}
