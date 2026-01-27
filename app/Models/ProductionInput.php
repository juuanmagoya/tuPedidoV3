<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionInput extends Model
{
    use HasFactory;

    protected $table = 'production_inputs';
    protected $fillable = [
        'production_id',   // Producción a la que pertenece
        'insumo_id',       // Insumo utilizado
        'quantity_used',   // Cantidad consumida
        'unit',            // Unidad (kg, g, lt, etc.)
        'cost_price',      // Precio costo unitario al momento
        'subtotal',        // Subtotal (cantidad * costo)
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Pertenece a una producción
    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    // Pertenece a un insumo (inputs)
    public function input()
    {
        return $this->belongsTo(Input::class, 'inputs_id');
    }
}
