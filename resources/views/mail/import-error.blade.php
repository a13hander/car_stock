@php
/** @var \Stock\Dto\IncompleteCar[] $cars */
@endphp

@component('mail::table')
|Vin|Ошибка|
|---|---|
@foreach($cars as $car)
|{{ $car->getCar()->vin }}|{{ $car->getErrors()[0] }}|
@endforeach
@endcomponent
