<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
   use HasFactory;
    protected $fillable = [
    'name',
    'cost_price',
    'selling_price',
    'stock',
    'category_id',
    'unit_id',
    'min_stock'
];
   public function category()
   {
    return $this->belongsTo(Category::class);
   }

   public function adjustments()
   {
    return $this->hasMany(StockAdjustment::class);
   }

   public function unit()
   {
    return $this->belongsTo(Unit::class);
   }

}

