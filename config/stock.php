<?php

return [
    'feed' => [
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
    ],

    'google-doc' => [
        'spreadsheet_id' => null,
        'range' => 'A1:R1000',

        // карта соответствий полей в базе данных и номеров колонок в таблице
        'fields_map' => [
            'brand' => 0,
            'body_type' => 1,
            'model' => 2,
            'modification' => 3,
            'equipment' => 4,
            'vin' => 5,
            'engine_power' => 6,
            'engine_volume' => 7,
            'fuel_type' => 8,
            'gearbox_type' => 9,
            'drive_type' => 10,
            'color' => 11,
            'year' => 13,
            'price' => 17,
        ],
    ],

    'show_only_with_images' => true,
    'use_soft_delete' => true,

    'car_error_mail_list' => [
        'test-cases@tapir.ws',
    ]
];
