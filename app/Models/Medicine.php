<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\MedicineController;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medicine extends Model
{
 use HasFactory;
use SoftDeletes; 
    protected $fillable = [
        'name',
        'description',
        'batch',
        'expiration_date',
        'stock',
        'price',
        'category',
        'minimum_stock',
          'fornecedor_id',
    ];
 protected $date = ['deleted_at'];
    protected $dates = ['expiration_date'];

protected $casts = [
    'critical_level' => 'string',
];

public function getStockLevelAttribute()
{
    if ($this->stock <= 0) {
        return 'out_of_stock';
    }
    
    $percentage = ($this->stock / $this->minimum_stock) * 100;
    
    if ($percentage < 20) {
        return 'critical';
    } elseif ($percentage < 50) {
        return 'high_alert';
    } elseif ($percentage < 100) {
        return 'warning';
    }
    
    return 'normal';
}

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

      public function needsRestock(): bool
    {
        return $this->stock < $this->minimum_stock;
    }

    // Atualiza o estoque
    public function updateStock(int $quantity): void
    {
        $this->stock += $quantity;
        $this->save();
    }

    // Relação com pedidos de reabastecimento
    public function restockOrders()
    {
        return $this->hasMany(RestockOrder::class);
    }
 public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }
    // Adicione este relacionamento
    public function supplyOrders()
    {
        return $this->hasMany(SupplyOrder::class);
    }

    // Métodos auxiliares para verificação de estoque
    public function isLowStock()
    {
        return $this->stock <= $this->minimum_stock;
    }

    public function isOutOfStock()
    {
        return $this->stock == 0;
    }

    public function needsRestock2()
    {
        return $this->isLowStock() || $this->isOutOfStock();
    }

    public function hasPendingOrders()
    {
        return $this->supplyOrders()
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }

    /**
 * The "booted" method of the model.
 *//*
protected static function booted()
{
    static::updated(function ($medicine) {
        // Verifica se o estoque foi alterado para abaixo do mínimo
        if ($medicine->isDirty('stock') && $medicine->stock < $medicine->minimum_stock) {
            $message = "O medicamento {$medicine->name} (Lote: {$medicine->batch}) "
                      ."está com estoque baixo. Quantidade atual: {$medicine->stock}, "
                      ."Mínimo: {$medicine->minimum_stock}";
            
            app(MedicineController::class)->createStockNotification($medicine, $message);
        }
        
        // Verifica se a data de validade foi alterada para uma data próxima
        if ($medicine->isDirty('expiration_date') && $medicine->expiration_date <= now()->addDays(30)) {
            $expirationDate = Carbon::parse($medicine->expiration_date)->format('d/m/Y');
            $message = "O medicamento {$medicine->name} (Lote: {$medicine->batch}) "
                      ."está próximo da data de expiração ({$expirationDate}).";
            
            app(MedicineController::class)->createExpirationNotification($medicine, $message);
        }
    });
}


public function checkStockLevels()
{
    if ($this->stock < $this->minimum_stock) {
        $users = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'farmacêutico', 'gerente']);
            })
            ->get();

        foreach ($users as $user) {
            $user->notify(new LowStockNotification($this));
        }
    }
}*/

    }
