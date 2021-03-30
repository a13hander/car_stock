<?php

namespace App\Models\CarStock;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $brand_id
 * @property string $full_name
 * @property Brand $brand
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
}
