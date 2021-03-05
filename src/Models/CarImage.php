<?php

namespace Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property boolean $is_main
 * @property integer $pos
 *
 * @property integer $car_id
 * @property Car $car
 */
class CarImage extends Model
{
    protected $table = 'stock_car_images';
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }
}
