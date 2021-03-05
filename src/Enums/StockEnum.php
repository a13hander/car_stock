<?php

namespace Stock\Enums;

class StockEnum
{
    public const TYPE_NEW = 'new';
    public const TYPE_USED = 'used';

    public const BODY_TYPE_HATCHBACK = 'hatchback';
    public const BODY_TYPE_UNIVERSAL = 'universal';
    public const BODY_TYPE_SEDAN = 'sedan';
    public const BODY_TYPE_SUV = 'suv';
    public const BODY_TYPE_PICKUP = 'pickup';
    public const BODY_TYPE_COMPACTVAN = 'compactvan';
    public const BODY_TYPE_COUPE = 'coupe';
    public const BODY_TYPE_MINIVAN = 'minivan';
    public const BODY_TYPE_CABRIOLET = 'cabriolet';
    public const BODY_TYPE_CROSSOVER = 'crossover';
    public const BODY_TYPE_LIFTBACK = 'liftback';
    public const BODY_TYPE_MAPPING = [
        self::BODY_TYPE_SUV => 'Внедорожник',
        self::BODY_TYPE_COUPE => 'Купе',
        self::BODY_TYPE_CABRIOLET => 'Кабриолет',
        self::BODY_TYPE_CROSSOVER => 'Кроссовер',
        self::BODY_TYPE_MINIVAN => 'Минивэн',
        self::BODY_TYPE_PICKUP => 'Пикап',
        self::BODY_TYPE_SEDAN => 'Седан',
        self::BODY_TYPE_UNIVERSAL => 'Универсал',
        self::BODY_TYPE_COMPACTVAN => 'Фургон',
        self::BODY_TYPE_HATCHBACK => 'Хетчбэк',
        self::BODY_TYPE_LIFTBACK => 'Лифтбек',
    ];

    public const WHEEL_TYPE_LEFT = 'left';
    public const WHEEL_TYPE_RIGHT = 'right';
    public const WHEEL_TYPE_MAPPING = [
        self::WHEEL_TYPE_LEFT => 'Левый',
        self::WHEEL_TYPE_RIGHT => 'Правый',
    ];

    public const FUEL_TYPE_GAS = 'gas';
    public const FUEL_TYPE_PETROL = 'petrol';
    public const FUEL_TYPE_DIESEL = 'diesel';
    public const FUEL_TYPE_HYBRID = 'hybrid';
    public const FUEL_TYPE_ELECTRIC = 'electric';
    public const FUEL_TYPE_MAPPING = [
        self::FUEL_TYPE_PETROL => 'Бензин',
        self::FUEL_TYPE_DIESEL => 'Дизель',
        self::FUEL_TYPE_HYBRID => 'Гибрид',
        self::FUEL_TYPE_ELECTRIC => 'Электро',
        self::FUEL_TYPE_GAS => 'Газ',
    ];

    public const TRANSMISSION_TYPE_MANUAL = 'manual';
    public const TRANSMISSION_TYPE_VARIATOR = 'variator';
    public const TRANSMISSION_TYPE_AUTOMATIC = 'automatic';
    public const TRANSMISSION_TYPE_ROBOTIZED = 'robotized';
    public const TRANSMISSION_TYPE_MAPPING = [
        self::TRANSMISSION_TYPE_AUTOMATIC => 'Автомат',
        self::TRANSMISSION_TYPE_VARIATOR => 'Вариатор',
        self::TRANSMISSION_TYPE_MANUAL => 'Механика',
        self::TRANSMISSION_TYPE_ROBOTIZED => 'Робот',
    ];

    public const DRIVE_TYPE_FRONT = 'front';
    public const DRIVE_TYPE_REAR = 'rear';
    public const DRIVE_TYPE_FULL_4WD = 'full_4wd';
    public const DRIVE_TYPE_MAPPING = [
        self::DRIVE_TYPE_FRONT => 'Передний',
        self::DRIVE_TYPE_REAR => 'Задний',
        self::DRIVE_TYPE_FULL_4WD => 'Полный',
    ];
}
