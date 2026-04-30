<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseCost extends Model
{
    protected $fillable = ['purchase_id', 'cost_type', 'description', 'amount'];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
// Add to Purchase model
public function costs(): HasMany
{
    return $this->hasMany(PurchaseCost::class);
}

public function getLandedTotalAttribute(): float
{
    return $this->total_cost + $this->extra_costs_total;
}
}

