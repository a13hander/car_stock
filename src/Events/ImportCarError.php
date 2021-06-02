<?php

namespace Stock\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Stock\Dto\IncompleteCar;

class ImportCarError
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * ImportCarError constructor.
     * @param IncompleteCar[] $failedCars
     */
    public function __construct(
        private array $failedCars,
    )
    {
    }

    /**
     * @return IncompleteCar[]
     */
    public function getFailedCars(): array
    {
        return $this->failedCars;
    }
}
