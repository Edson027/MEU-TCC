<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
 use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'batch',
        'expiration_date',
        'stock',
        'price',
        'category',
        'minimum_stock'
    ];

    protected $dates = ['expiration_date'];

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    // Accessor para status do estoque
    public function getStockStatusAttribute()
    {
        if ($this->stock == 0) {
            return 'Esgotado';
        } elseif ($this->stock < $this->minimum_stock) {
            return 'Crítico';
        } elseif ($this->stock < ($this->minimum_stock * 1.5)) {
            return 'Atenção';
        }
        return 'Normal';
    }

    // Accessor para status da validade
    public function getExpirationStatusAttribute()
    {
        $days = now()->diffInDays($this->expiration_date, false);

        if ($days < 0) {
            return 'Vencido';
        } elseif ($days <= 30) {
            return 'Próximo a vencer';
        }
        return 'OK';
    }

    }
