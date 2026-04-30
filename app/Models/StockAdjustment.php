<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = [
    'product_id',
    'quantity',
    'cost_price',
    'total_cost',
    'type',
    'reason',
    'date'
];

public function product()
{
    return $this->belongsTo(Product::class);
}

}