<?php

namespace Stock\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Collection|CarModel[] $car_models
 */
class Brand extends Model
{
    protected $table = 'stock_brands';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function car_models(): HasMany
    {
        return $this->hasMany(CarModel::class, 'brand_id', 'id');
    }
}
