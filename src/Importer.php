<?php

namespace Stock;

use Stock\Import\BrandImport;
use Stock\Import\CarsImport;
use Stock\Import\LocationImport;
use Stock\Import\ModelImport;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class Importer
{
    public function __construct(
        private Fetcher $fetcher,
        private LocationImport $locationImport,
        private BrandImport $brandImport,
        private ModelImport $modelImport,
        private CarsImport $carsImport,
        private LoggerInterface $logger
    )
    {
    }

    public function import(bool $force = false): void
    {
        if (!$this->fetcher->hasDifferences() && !$force) {
            $this->logger->info('Импорт не содержит новых данных, для принудительного запуска добавьте ключ --force');
            return;
        }

        $result = $this->fetcher->fetch();
        $cars = collect($result->getCars());

        $this->importLocations($cars);
        $this->importBrands($cars);
        $this->importModels($cars);
        $this->importCars($cars);

        //if ($result->hasIncompleteCars()) {
        //    // отправка отчета
        //}
    }

    private function importLocations(Collection $cars): void
    {
        $this->locationImport->handle($cars);
        $newItems = $this->locationImport->getLastImportedItems();

        if ($newItems->isEmpty()) {
            $this->logger->info('Импорт не содержал новых локаций');
        } else {
            $this->logger->info('Новые локации: ' . $newItems->implode(', '));
        }
    }

    private function importBrands(Collection $cars): void
    {
        $this->brandImport->handle($cars);
        $newItems = $this->brandImport->getLastImportedItems();

        if ($newItems->isEmpty()) {
            $this->logger->info('Импорт не содержал новых брендов');
        } else {
            $this->logger->info('Новые бренды: ' . $newItems->implode(', '));
        }
    }

    private function importModels(Collection $cars): void
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

    private function importCars(Collection $cars): void
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
