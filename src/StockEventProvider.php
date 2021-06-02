<?php

namespace Stock;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Stock\Events\ImportCarError;
use Stock\Listeners\SendImportCarErrorListener;

class StockEventProvider extends ServiceProvider
{
    protected $listen = [
        ImportCarError::class => [
            SendImportCarErrorListener::class
        ],
    ];
}
