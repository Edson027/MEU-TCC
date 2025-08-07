<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
   use SoftDeletes;

    protected $fillable = [
        'medicine_id',
        'quantity',
        'status',
        'requested_by',
        'approved_by',
        'notes',
        'urgency_level'
    ];

    protected $dates = ['deleted_at'];

    // Níveis de urgência
    const URGENCY_LOW = 'low';
    const URGENCY_MEDIUM = 'medium';
    const URGENCY_HIGH = 'high';

    // Status do pedido
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
