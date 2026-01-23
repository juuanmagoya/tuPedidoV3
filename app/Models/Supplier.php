<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tax_id',
        'phone',
        'email',
        'address',
        'contact_name',
        'payment_terms',
        'notes',
        'is_active',
    ];
}
