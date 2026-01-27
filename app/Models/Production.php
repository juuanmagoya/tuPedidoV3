<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',              // Código único de la producción
        'production_date',   // Fecha de producción
        'total_cost',        // Costo total de la producción
        'status',            // Estado: draft | confirmed | cancelled
        'notes',             // Observaciones
        'created_by',        // Usuario que creó la producción
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Una producción consume muchos insumos
    public function inputs()
    {
        return $this->hasMany(ProductionInput::class);
    }

    // Una producción puede generar uno o varios productos
    public function products()
    {
        return $this->hasMany(ProductionProduct::class);
    }

    // Usuario que registró la producción
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
