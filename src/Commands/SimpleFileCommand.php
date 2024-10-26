<?php

namespace MichaelBecker\SimpleFile\Commands;

use Illuminate\Console\Command;

class SimpleFileCommand extends Command
{
    public $signature = 'laravel-simple-file-model';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
