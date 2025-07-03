<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'user_id',
        'type',
        'quantity',
        'reason',
        'movement_date',
        'related_request_id'
    ];

    protected $casts = [
        'movement_date' => 'datetime'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function request()
    {
        return $this->belongsTo(Request::class, 'related_request_id');
    }

    public function getTypeNameAttribute()
    {
        return $this->type === 'entrada' ? 'Entrada' : 'Saída';
    }

    public function getTypeColorAttribute()
    {
        return $this->type === 'entrada' ? 'green' : 'red';
    }
/*
      public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }

    // Escopo para movimentações de saída
    public function scopeOutgoing($query)
    {
        return $query->where('type', 'saida');
    }
*/
    // Escopo para movimentações de entrada
    public function scopeIncoming($query)
    {
        return $query->where('type', 'entrada');
    }

 public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }

    /**
     * Escopo para filtrar apenas saídas (consumo).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOutgoing($query)
    {
        return $query->where('type', 'saida');
    }

}
