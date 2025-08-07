<?php

namespace App\Observers;

use App\Models\Medicine;
use App\Notifications\LowStockAlert;
use App\Events\LowStockEvent;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
class MedicineObserver
{
    /**
     * Handle the Medicine "created" event.
     */
    public function created(Medicine $medicine): void
    {
        //
    }

    /**
     * Handle the Medicine "updated" event.
     */
    public function updated2(Medicine $medicine): void
    {
       if ($medicine->stock < $medicine->minimum_stock && $medicine->isDirty('stock')) {
            // Dispara evento
            event(new LowStockEvent($medicine));
            
            // Notifica usuários responsáveis
            $users = User::where('painel', 'administrador')->orWhere('painel', 'responsavel')->get();
            Notification::send($users, new LowStockAlert($medicine));
        }
    }

    public function updated(Medicine $medicine)
{
    $now = now();
    $lastAlerted = $medicine->last_alerted_at;
    
    // Verifica se deve enviar alerta (estoque baixo E (nunca alertou OU último alerta há mais de 24h))
    if ($medicine->stock < $medicine->minimum_stock && 
        (!$lastAlerted || $lastAlerted->diffInHours($now) >= 24)) {
        
        event(new LowStockEvent($medicine));
        
        $users = User::whereIn('painel', ['administrador', 'manager'])->get();
        Notification::send($users, new LowStockAlert($medicine));
        
        // Atualiza o último alerta
        $medicine->update(['last_alerted_at' => $now]);
    }
}
    /**
     * Handle the Medicine "deleted" event.
     */
    public function deleted(Medicine $medicine): void
    {
        //
    }

    /**
     * Handle the Medicine "restored" event.
     */
    public function restored(Medicine $medicine): void
    {
        //
    }

    /**
     * Handle the Medicine "force deleted" event.
     */
    public function forceDeleted(Medicine $medicine): void
    {
        //
    }
}
