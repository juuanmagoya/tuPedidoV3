<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'customer_id',     // ID del cliente (puede ser null)
        'customer_name',   // Nombre del cliente
        'status',          // Estado del pedido
        'order_type',      // Tipo de pedido: delivery | in_store
        'address',         // Dirección (solo si es delivery)
        'payment_method',  // Método de pago
        'notes',           // Observaciones del pedido
        'total',           // Total calculado del pedido
    ];

    /**
     * Relación con el cliente
     * Un pedido puede pertenecer a un cliente
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relación con los productos del pedido
     * Un pedido tiene muchos productos (detalle)
     */
    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, [
            'received',
            'preparing',
        ]);
    }
    /*public function items()
    {
        return $this->hasMany(OrderProduct::class);
    }*/
    


}
