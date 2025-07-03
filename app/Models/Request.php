<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class Request extends Model
{
 use HasFactory;

    protected $fillable = [
        'user_id',
        'medicine_id',
        'quantity',
        'reason',
        'status',
        'response',
        'responded_by',
        'urgency_level'
    ];

    protected $attributes = [
        'status' => 'pending',
        'urgency_level' => 'normal'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function movements()
    {
        return $this->hasMany(Movement::class, 'related_request_id');
    }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'yellow',
            'approved' => 'green',
            'partial' => 'blue',
            'rejected' => 'red'
        ][$this->status];
    }

    public function getUrgencyColorAttribute()
    {
        return [
            'normal' => 'gray',
            'urgente' => 'orange',
            'muito_urgente' => 'red'
        ][$this->urgency_level];
    }

      public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });

        $query->when($filters['urgency'] ?? null, function ($query, $urgency) {
            $query->where('urgency_level', $urgency);
        });
    }
}
