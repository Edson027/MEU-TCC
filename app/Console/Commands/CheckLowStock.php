<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medicine;
use App\Notifications\LowStockAlert;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    protected $signature = 'app:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
  //  protected $description = 'Command description';
  protected $signature = 'stock:check-low';
    protected $description = 'Check for medicines with stock below minimum';

    public function handle2()
    {
        $medicines = Medicine::whereColumn('stock', '<', 'minimum_stock')->get();
        
        if ($medicines->isNotEmpty()) {
            $users = User::whereIn('role', ['admin', 'manager'])->get();
            
            foreach ($medicines as $medicine) {
                Notification::send($users, new LowStockAlert($medicine));
            }
            
            $this->info('Notificações de estoque baixo enviadas para ' . $medicines->count() . ' medicamentos.');
        } else {
            $this->info('Nenhum medicamento com estoque abaixo do mínimo encontrado.');
        }
    }

    public function handle()
{
    $medicines = Medicine::whereColumn('stock', '<', 'minimum_stock')
        ->where(function($query) {
            $query->whereNull('last_alerted_at')
                  ->orWhere('last_alerted_at', '<', now()->subHours(24));
        })
        ->get();
    
    if ($medicines->isNotEmpty()) {
        $users = User::whereIn('role', ['admin', 'manager'])
                   ->whereNotNull('phone')
                   ->get();
        
        foreach ($medicines as $medicine) {
            Notification::send($users, new LowStockAlert($medicine));
            $medicine->update(['last_alerted_at' => now()]);
        }
        
        $this->info('Notificações enviadas para ' . $medicines->count() . ' medicamentos.');
    }
}
}
