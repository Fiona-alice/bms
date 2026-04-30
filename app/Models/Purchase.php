<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{

protected $fillable = [
        'product_id',
        'quantity',
        'cost_price',
        'landed_unit_cost',
        'extra_costs_total',
        'total_cost',
        'date',];
 public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function costs(): HasMany
    {
        return $this->hasMany(PurchaseCost::class);
    }

    // ── Accessors ──────────────────────────────────
    public function getLandedTotalAttribute(): float
    {
        return $this->total_cost + $this->extra_costs_total;
    }
}
