<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckStockLevels extends Command
{
    protected $signature = 'stock:check';
    protected $description = 'Verifica níveis de estoque e cria pedidos automáticos';

    public function handle(StockMonitoringService $stockService)
    {
        $ordersCreated = $stockService->createSupplyOrders();
        
        if ($ordersCreated > 0) {
            $this->info("{$ordersCreated} pedidos de abastecimento criados automaticamente.");
        } else {
            $this->info("Nenhum pedido necessário no momento.");
        }
    }
}
