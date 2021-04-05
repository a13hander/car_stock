<?php

namespace App\Models\CarStock;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $brand_id
 * @property string $full_name
 * @property Brand $brand
 * @property Car[]|Collection $cars
 */
class CarModel extends Model
{
    protected $table = 'stock_car_models';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'model_id', 'id');
    }
}
