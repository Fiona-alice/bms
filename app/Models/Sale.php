<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
    'product_id',
    'quantity',
    'selling_price',
    'unit_price',
    'cost_price',
    'total_price',
    'total_cost',
    'profit',
    'date'

];

public function product()
{
    return $this->belongsTo(Product::class);
}
}
