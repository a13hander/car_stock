<?php

namespace Stock\Commands;

use Illuminate\Console\Command;
use Stock\FeedImporter;
use Stock\GoogleDocImporter;

class ImportAllCommand extends Command
{
    protected $signature = 'stock:import {--force}';

    protected $description = 'Import cars';

    public function handle(FeedImporter $feedImporter, GoogleDocImporter $docImporter)
    {
        $force = $this->option('force') ?? false;

        $feedImporter->import($force);
        $docImporter->import($force);
    }
}
