<?php

namespace Stock\Commands;

use Stock\Importer;
use Illuminate\Console\Command;

class ImportCommand extends Command
{
    protected $signature = 'stock:import {--force}';

    protected $description = 'Import cars';

    public function handle(Importer $importer)
    {
        $force = $this->option('force') ?? false;

        $importer->import($force);
    }
}
