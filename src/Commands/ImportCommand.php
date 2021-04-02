<?php

namespace Stock\Commands;

use Illuminate\Console\Command;
use InvalidArgumentException;
use Stock\FeedImporter;
use Stock\GoogleDocImporter;

class ImportCommand extends Command
{
    protected $signature = 'stock:import {importer} {--force}';

    protected $description = 'Import cars';

    protected $importers = [
        'feed' => FeedImporter::class,
        'google-doc' => GoogleDocImporter::class,
    ];

    public function handle()
    {
        $force = $this->option('force') ?? false;

        if (!array_key_exists($this->argument('importer'), $this->importers)) {
            throw new InvalidArgumentException('Указан некорректный importer. Доступные значения: ' . implode(', ', array_keys($this->importers)));
        }

        $importer = app()->make($this->importers[$this->argument('importer')]);
        $importer->import($force);
    }
}
