<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{

    const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE = 'active';
    const STATUS_PROMOTION = 'promotion';
    const STATUS_FEATURED = 'featured';
    const STATUS_OUT_OF_STOCK = 'out_of_stock';

    const STATUS_LABELS = [
        self::STATUS_INACTIVE => 'Inactivo',
        self::STATUS_ACTIVE => 'Activo',
        self::STATUS_PROMOTION => 'En promoción',
        self::STATUS_FEATURED => 'Destacado',
        self::STATUS_OUT_OF_STOCK => 'Agotado',
    ];

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
    ];

    /* =====================
       Relaciones
    ===================== */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
