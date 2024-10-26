<?php

namespace MichaelBecker\SimpleFile\Tests;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MichaelBecker\SimpleFile\Models\Traits\HasFiles;

class TestModel extends Model
{
    use HasFactory, HasFiles, HasUuids, SoftDeletes;

    protected $table = 'test_models';

    protected $guarded = [];

    const DISK = 'public';

    protected static function newFactory()
    {
        return TestModelFactory::new();
    }
}
