<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OrderProduct extends Model
{
    use HasFactory;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'order_id',    // ID del pedido
        'product_id',  // ID del producto
        'quantity',    // Cantidad pedida
        'unit_price',  // Precio unitario al momento del pedido
        'subtotal',    // Subtotal (quantity * unit_price)
    ];

    /**
     * Relación con el pedido
     * El detalle pertenece a un pedido
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con el producto
     * El detalle pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
