<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Variation extends Model
{
    use HasFactory;

    public function getTotalStock(): int
    {
        $total = 0;
        foreach ($this->inventories as $inv){
            $total += $inv->stock;
        }
        return $total;
    }

    public function getSortedInventory(){
        return Inventory::where('variation_id', '=', $this->id)->orderBy('expire_date')->get();
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function discount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VariationDiscount::class);
    }

    public function inventories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}