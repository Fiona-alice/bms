<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'product_id',
        'client_id',
        'quantity',
        'rental_price',
        'date_out',
        'expected_return',
        'return_date',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

   public function client()
{
    return $this->belongsTo(Client::class);
}
}
