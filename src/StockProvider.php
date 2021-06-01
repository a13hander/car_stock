<?php

namespace Stock;

use Stock\Commands\ImportCommand;
use Stock\Events\ImportCarError;
use Stock\Fetchers\Feed\FeedFetcher;
use Stock\Fetchers\Fetcher;
use Stock\Fetchers\Feed\FetcherConfig;
use Stock\Fetchers\GoogleDoc\GoogleDocFetcher;
use Stock\Listeners\SendImportCarErrorListener;
use Stock\Parsers\Feed\XmlParser;
use Stock\Parsers\GoogleDoc\GoogleDocParser;
use Stock\Validation\DefaultValidator;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use GuzzleHttp\Client as HttpClient;
use Stock\Validation\Validator;

class StockProvider extends ServiceProvider
{
    protected $listen = [
        ImportCarError::class => [
            SendImportCarErrorListener::class
        ],
    ];

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
            __DIR__ . '/../stubs/Models/' => app_path('Models/CarStock')
        ], 'models');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'stock');
    }

    public function register()
    {
        parent::register();

        $this->app->bind(Validator::class, function () {
            return new DefaultValidator();
        });

        //импорт feed
        $this->app->when(FeedImporter::class)->needs(Fetcher::class)->give(function (Application $app) {
            $httpClient = $app->make(HttpClient::class);
            $parser = $app->make(XmlParser::class);

            $filename = storage_path('app/' . config('stock.feed.filename'));
            $previousFilename = storage_path('app/' . 'previous_' . config('stock.feed.filename'));

            $config = new FetcherConfig(
                config('stock.feed.source_url'),
                $filename,
                $previousFilename,
            );

            return new FeedFetcher($httpClient, $parser, $config);
        });
        $this->app->bind(XmlParser::class, function (Application $app) {
            return new XmlParser(
                $app->make(Validator::class),
                config('stock.feed.fields_map')
            );
        });

        //импорт google doc
        $this->app->bind(GoogleDocParser::class, function (Application $app) {
            return new GoogleDocParser(
                $app->make(Validator::class),
                config('stock.google-doc.fields_map')
            );
        });
        $this->app->when(GoogleDocImporter::class)->needs(Fetcher::class)->give(function (Application $app) {
            return new GoogleDocFetcher(
                $this->app->make(GoogleDocParser::class),
                config('stock.google-doc')
            );
        });
    }
}
