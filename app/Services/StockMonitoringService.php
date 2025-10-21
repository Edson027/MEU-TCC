<?php

namespace App\Services;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\SupplyOrder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockMonitoringService
{
    public function checkLowStock()
    {
        $medicines = Medicine::where(function($query) {
            $query->where('stock', '<=', DB::raw('minimum_stock'))
                  ->orWhere('stock', 0);
        })->get();

        // Filtra medicamentos que não têm pedidos pendentes
        $medicinesWithoutPendingOrders = $medicines->filter(function($medicine) {
            return !$medicine->hasPendingOrders();
        });

        return $medicinesWithoutPendingOrders;
    }

    public function createSupplyOrders()
    {
        $medicines = $this->checkLowStock();
        $ordersCreated = 0;

        foreach ($medicines as $medicine) {
            // Calcula a quantidade necessária (estoque mínimo + margem de segurança)
            $quantityNeeded = $medicine->minimum_stock * 2 - $medicine->stock;
            $quantityNeeded = max($quantityNeeded, $medicine->minimum_stock);

            // Garante que a quantidade seja positiva
            $quantityNeeded = max($quantityNeeded, 1);

            try {
                SupplyOrder::create([
                    'order_number' => 'SO-' . date('YmdHis') . '-' . str_pad($medicine->id, 4, '0', STR_PAD_LEFT),
                    'medicine_id' => $medicine->id,
                    'quantity_requested' => $quantityNeeded,
                    'reason' => 'Estoque abaixo do mínimo. Estoque atual: ' . $medicine->stock . ', Mínimo: ' . $medicine->minimum_stock,
                    'request_date' => Carbon::now(),
                    'requested_by' => auth()->id() ?? 1, // ID do usuário admin padrão
                    'status' => 'pending'
                ]);

                // Atualiza a última data de alerta
                $medicine->update(['last_alerted_at' => Carbon::now()]);
                $ordersCreated++;
            } catch (\Exception $e) {
                // Log do erro se necessário
                \Log::error('Erro ao criar pedido para medicamento ' . $medicine->id . ': ' . $e->getMessage());
            }
        }

        return $ordersCreated;
    }

    public function getStockAlerts()
    {
        return Medicine::where('stock', '<=', DB::raw('minimum_stock'))
            ->orWhere('stock', 0)
            ->with(['supplyOrders' => function($query) {
                $query->whereIn('status', ['pending', 'approved']);
            }])
            ->get();
    }

    // Novo método para API de notificações
    public function getNotificationsData()
    {
        $criticalStock = Medicine::where('stock', '>', 0)
            ->where('stock', '<=', DB::raw('minimum_stock'))
            ->get();

        $zeroStock = Medicine::where('stock', 0)->get();

        $expiringSoon = Medicine::where('expiration_date', '<=', Carbon::now()->addDays(30))
            ->where('expiration_date', '>', Carbon::now())
            ->get();

        return [
            'critical_stock' => $criticalStock,
            'zero_stock' => $zeroStock,
            'expiring_soon' => $expiringSoon,
        ];
    }
}
