<?php

namespace App\Models\CarStock;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property boolean $is_main
 * @property integer $pos
 * @property string $image
 * @property string $original_image
 * @property boolean $from_import
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
        'from_import' => 'boolean',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }

    public function scopeImported(Builder $builder): Builder
    {
        return $builder->where('from_import', 1);
    }
}
