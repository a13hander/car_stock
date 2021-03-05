<?php

return [
    'source_url' => null,
    'filename' => 'stock.xml',

    // карта соответствий полей в выгрузке и в базе данных
    'fields_map' => [
        'id' => 'Id',
        'vin' => 'VIN',
        'brand' => 'Make',
        'model' => 'Model',
        'body_type' => 'BodyType',
        'fuel_type' => 'FuelType',
        'drive_type' => 'DriveType',
        'gearbox_type' => 'Transmission',
        'wheel_type' => 'WheelType',
        'engine_power' => 'Power',
        'engine_volume' => 'EngineSize',
        'doors' => 'Doors',
        'year' => 'Year',
        'kilometrage' => 'Kilometrage',
        'color' => 'Color',
        'accident' => 'Accident',
        'owners' => 'Owners',
        'expiryDate' => 'DateEnd',
        'address' => 'Address',
        'description' => 'Description',
        'price' => 'Price',
        'images' => 'Images',
    ],
];
