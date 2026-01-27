<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionProduct extends Model
{
    use HasFactory;

    protected $table = 'production_products';

    protected $fillable = [
        'production_id',      // Producción relacionada
        'product_id',         // Producto fabricado
        'quantity_produced',  // Cantidad producida
        'unit',               // Unidad del producto
        'cost_price',         // Precio costo unitario resultante
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

    // Pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
