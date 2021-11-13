<?php

namespace EasyMeiTuan\Models;

/**
 * Class Product
 *
 * @property string  $app_food_code
 * @property string  $name
 * @property numeric $price
 * @property integer $min_order_count
 * @property numeric $box_price
 * @property integer $box_num
 * @property string  $unit
 * @property string  $category_name
 * @property integer $is_sold_out
 * @property string  $picture
 * @property integer $ctime
 * @property integer $utime
 * @property string  $location_code
 * @property array   $skus
 */
class Product extends Model
{
    public function getSkusAttribute(): array
    {
        return \json_decode($this->getOriginal('skus'), true);
    }
}
