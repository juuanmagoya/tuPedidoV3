<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id',
        'input_id',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    
    public function input(): BelongsTo
    {
        return $this->belongsTo(Input::class);
    }
}
