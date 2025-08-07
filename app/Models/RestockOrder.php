<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestockOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'medicine_id', 'quantity_requested', 'status', 
        'rejection_reason', 'requested_by', 'processed_by'
    ];

    protected $dates = ['processed_at'];

    // Estados possíveis
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Relação com o produto
    public function product()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Relação com o usuário que solicitou
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // Relação com o usuário que processou
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Aprovar o pedido
    public function approve(User $processor): void
    {
        $this->status = self::STATUS_APPROVED;
        $this->processed_by = $processor->id;
        $this->processed_at = now();
        $this->save();

        // Atualizar o estoque do produto
        $this->product->updateStock($this->quantity_requested);
    }

    // Rejeitar o pedido
    public function reject(User $processor, string $reason): void
    {
        $this->status = self::STATUS_REJECTED;
        $this->processed_by = $processor->id;
        $this->processed_at = now();
        $this->rejection_reason = $reason;
        $this->save();
    }

    // Verificar se está pendente
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
