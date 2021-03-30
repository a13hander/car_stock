<?php

namespace Stock;

use Stock\Commands\ImportCommand;
use Stock\Validation\DefaultValidator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as HttpClient;
use Stock\Validation\Validator;

class StockProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->commands([
            ImportCommand::class,
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../config/stock.php', 'stock'
        );

        $this->publishes([
            __DIR__ . '/../config/stock.php' => config_path('stock.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/Models/' => app_path('Models/CarStock')
        ], 'models');
    }

    public function register()
    {
        $this->app->bind(Validator::class, function () {
            return new DefaultValidator();
        });

        $this->app->bind(Parser::class, function (Application $app) {
            return new XmlParser(
                $app->make(Validator::class),
                config('stock.fields_map')
            );
        });

        $this->app->bind(Fetcher::class, function (Application $app) {
            $httpClient = $app->make(HttpClient::class);
            $parser = $app->make(Parser::class);

            $filename = storage_path('app/' . config('stock.filename'));
            $previousFilename = storage_path('app/' . 'previous_' . config('stock.filename'));

            $config = new FetcherConfig(
                config('stock.source_url'),
                $filename,
                $previousFilename,
            );

            return new Fetcher($httpClient, $parser, $config);
        });
    }
}
