<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
      use HasFactory;

    protected $fillable = [
        'medicine_id',
        'requested_by',
        'quantity',
        'priority',
        'notes',
        'status',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime'
    ];

    // Constantes para prioridades
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Constantes para status
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getPriorities()
    {
        return [
            self::PRIORITY_LOW => 'Baixa',
            self::PRIORITY_MEDIUM => 'Média',
            self::PRIORITY_HIGH => 'Alta',
            self::PRIORITY_URGENT => 'Urgente'
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_PROCESSING => 'Em Processamento',
            self::STATUS_COMPLETED => 'Concluído',
            self::STATUS_CANCELLED => 'Cancelado'
        ];
    }

    // Relacionamentos
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Escopos
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

      const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PENDING1 = 'pending';
    const STATUS_PROCESSING1 = 'processing';
    const STATUS_COMPLETED1 = 'completed';

    public static function getStatuses2()
    {
        return [
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_APPROVED => 'Aprovado',
            self::STATUS_REJECTED => 'Rejeitado',
            self::STATUS_PROCESSING => 'Em Processamento',
            self::STATUS_COMPLETED => 'Concluído'
        ];
    }

    // Escopo para pedidos que precisam de aprovação
    public function scopeNeedsApproval($query)
    {
        return $query->where('status', self::STATUS_PENDING)
                    ->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    // Verifica se o pedido pode ser aprovado
    public function canBeApproved()
    {
        return $this->status === self::STATUS_PENDING && 
               in_array($this->priority, [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    // Verifica se o pedido pode ser rejeitado
    public function canBeRejected()
    {
        return $this->status === self::STATUS_PENDING && 
               in_array($this->priority, [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    
}
