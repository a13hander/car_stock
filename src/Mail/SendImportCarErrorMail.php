<?php

namespace Stock\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Stock\Dto\IncompleteCar;

class SendImportCarErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * SendImportCarErrorMail constructor.
     * @param IncompleteCar[] $failedCars
     */
    public function __construct(
        private array $failedCars
    )
    {
    }

    public function build()
    {
        return $this
            ->subject('Ошибка при импорте авто')
            ->markdown('stock::mail.import-error', [
                'cars' => $this->failedCars
            ]);
    }
}
