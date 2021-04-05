<?php

namespace App\Models\CarStock;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stock\Enums\StockEnum;

/**
 * @property string $id
 * @property string $vin
 *
 * @property string $type
 *
 * @property string $equipment
 * @property string $modification
 *
 * @property string $body_type
 * @property string $fuel_type
 * @property string $drive_type
 * @property string $gearbox_type
 * @property string $wheel_type
 * @property string $engine_power
 * @property string $engine_volume
 *
 * @property int $doors
 * @property int|null $year
 * @property int $kilometrage
 *
 * @property string|null $color
 * @property string|null $color_hex
 *
 * @property int $price
 * @property int $credit_percent
 * @property int $discount
 *
 * @property string $description
 * @property string $accident
 * @property int $owners
 *
 * @property array|null $original_images
 * @property array|null $marketing_labels
 *
 * @property int $model_id
 * @property CarModel $car_model
 *
 * @property int $location_id
 * @property Location $location
 *
 * @property Collection $images
 *
 * @method Builder new()
 * @method Builder used()
 */
class Car extends Model
{
    use SoftDeletes;

    protected $table = 'stock_cars';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'original_images' => 'array',
        'marketing_labels' => 'array',
    ];

    public function car_model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'model_id', 'id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class, 'car_id', 'id');
    }

    public function getUrl(): string
    {
        $url = route("stock.car.show", [
            'brand_slug' => $this->car_model->brand->slug,
            'model_slug' => $this->car_model->slug,
            'car' => $this->id,
        ]);

        // слеш в конце урл обязателен
        return $url . '/';
    }

    public function getVinFormatted(): string
    {
        $vin = $this->vin;

        $vin[8] = '*';
        $vin[11] = '*';
        $vin[12] = '*';
        $vin[13] = '*';
        $vin[14] = '*';

        return $vin;
    }

    public function getMainImage(): ?CarImage
    {
        return $this->images->where('is_main', 1)->first() ?? $this->images->first() ?? null;
    }

    public function getPriceFormatted(): string
    {
        return number_format($this->price, 0, 0, ' ');
    }

    public function scopeNew(Builder $builder): Builder
    {
        return $builder->where('type', StockEnum::TYPE_NEW);
    }

    public function scopeUsed(Builder $builder): Builder
    {
        return $builder->where('type', StockEnum::TYPE_USED);
    }
}
