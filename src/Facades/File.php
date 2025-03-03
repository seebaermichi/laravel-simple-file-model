<?php

namespace MichaelBecker\SimpleFile\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MichaelBecker\SimpleFile\SimpleFile
 */
class File extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MichaelBecker\SimpleFile\SimpleFile::class;
    }
}
