<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'image',
        'price',
        'cost_price',
        'stock',
        'min_stock',
        'unit',
        'status',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'status' => 'boolean',
    ];

    /* =====================
       Relaciones
    ===================== */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
