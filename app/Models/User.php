<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    
    // =========================
    // ROLES
    // =========================
    const ROLE_ADMIN = 'admin';
    const ROLE_PRODUCTION = 'production_manager';
    const ROLE_PURCHASE = 'purchase_manager';

    // ============================
    // LABELS (ESPAÑOL)
    // ============================
    public const ROLE_LABELS = [
        self::ROLE_ADMIN      => 'Administrador',
        self::ROLE_PRODUCTION => 'Producción',
        self::ROLE_PURCHASE   => 'Compras',
    ];

    // =========================
    // STATUSES
    // =========================
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_PENDING = 'pending';

    public const STATUS_LABELS = [
        self::STATUS_ACTIVE   => 'Activo',
        self::STATUS_INACTIVE => 'Inactivo',
        self::STATUS_PENDING  => 'Pendiente',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
