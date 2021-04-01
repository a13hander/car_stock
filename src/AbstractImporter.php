<?php

namespace Stock;

use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Stock\Fetchers\Fetcher;
use Stock\Import\BrandImport;
use Stock\Import\CarsImport;
use Stock\Import\LocationImport;
use Stock\Import\ModelImport;

abstract class AbstractImporter
{
    public function __construct(
        protected Fetcher $fetcher,
        protected LocationImport $locationImport,
        protected BrandImport $brandImport,
        protected ModelImport $modelImport,
        protected CarsImport $carsImport,
        protected LoggerInterface $logger
    )
    {
    }

    abstract public function import(bool $force = false): void;

    protected function importLocations(Collection $cars): void
    {
        $this->locationImport->handle($cars);
        $newItems = $this->locationImport->getLastImportedItems();

        if ($newItems->isEmpty()) {
            $this->logger->info('Импорт не содержал новых локаций');
        } else {
            $this->logger->info('Новые локации: ' . $newItems->implode(', '));
        }
    }

    protected function importBrands(Collection $cars): void
    {
        $this->brandImport->handle($cars);
        $newItems = $this->brandImport->getLastImportedItems();

        if ($newItems->isEmpty()) {
            $this->logger->info('Импорт не содержал новых брендов');
        } else {
            $this->logger->info('Новые бренды: ' . $newItems->implode(', '));
        }
    }

    protected function importModels(Collection $cars): void
    {
        $this->modelImport->handle($cars);
        $newItems = $this->modelImport->getLastImportedItems();

        if ($newItems->isEmpty()) {
            $this->logger->info('Импорт не содержал новых моделей');
        } else {
            /**
             * @var string $brand
             * @var Collection $models
             */
            foreach ($newItems as $brand => $models) {
                if ($models->isNotEmpty()) {
                    $this->logger->info("Новые модели для бренда {$brand} : " . $models->implode(', '));
                }
            }
        }
    }

    protected function importCars(Collection $cars): void
    {
        $this->carsImport->handle($cars);
        $newItems = $this->carsImport->getLastImportedItems();

        if ($newItems->isEmpty()) {
            $this->logger->info('Импорт не содержал новых авто');
        } else {
            $this->logger->info('Новых авто: ' . $newItems->count());
        }
    }
}
