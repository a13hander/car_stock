<?php

namespace App\Models\CarStock;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $slug
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $full_address
 * @property string|null $time_open
 * @property string|null $time_close
 * @property string|null $coord_n
 * @property string|null $coord_e
 * @property string|null $navigator
 */
class Location extends Model
{
    protected $table = 'stock_locations';
    public $timestamps = false;

    protected $guarded = ['id'];
}
