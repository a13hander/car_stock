<?php

namespace Stock\Listeners;

use Illuminate\Support\Facades\Mail;
use Stock\Events\ImportCarError;
use Stock\Mail\SendImportCarErrorMail;

class SendImportCarErrorListener
{
    public function handle(ImportCarError $event)
    {
        if (count($event->getFailedCars()) > 0) {
            Mail::to(config('stock.car_error_mail_list', []))->queue(new SendImportCarErrorMail($event->getFailedCars()));
        }
    }
}
