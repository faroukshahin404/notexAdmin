<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'host',
        'port',
        'database',
        'username',
        'password',
        'email',
        'phone',
        'type',
        'monthly_payment',
        'is_installed',
        'installation_date',
        'expired_date',
    ];

    protected $casts = [
        'monthly_payment' => 'decimal:2',
        'is_installed' => 'boolean',
        // 'password' => 'encrypted',
    ];
}


