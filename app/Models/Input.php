<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Input extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'stock',
        'min_stock',
        'cost_price',
        'supplier_id',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'stock' => 'decimal:3',
        'min_stock' => 'decimal:3',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /* =========================
       Relaciones
       ========================= */

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
