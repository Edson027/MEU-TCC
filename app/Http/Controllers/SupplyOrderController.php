<?php

namespace App\Http\Controllers;

use App\Models\SupplyOrder;
use App\Models\Medicine;
use App\Services\StockMonitoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplyOrderController extends Controller
{
   protected $stockService;

    public function __construct(StockMonitoringService $stockService)
    {
        $this->stockService = $stockService;
    }

   public function index(Request $request)
{
    $query = SupplyOrder::with(['medicine', 'requester', 'approver']);

    // Filtros
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    if ($request->has('medicine_id') && $request->medicine_id != '') {
        $query->where('medicine_id', $request->medicine_id);
    }

    $supplyOrders = $query->orderBy('created_at', 'asc')->paginate(10);

    $medicines = Medicine::all();
    $stockAlerts = $this->stockService->getStockAlerts();

    return view('supply-orders.index', compact('supplyOrders', 'medicines', 'stockAlerts'));
}
 
    public function create()
    {
        $medicines = Medicine::all();
        return view('supply-orders.create', compact('medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity_requested' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
            'expected_delivery_date' => 'nullable|date|after:today'
        ]);

        try {
            DB::beginTransaction();

            $supplyOrder = SupplyOrder::create([
                'order_number' => 'SO-' . date('YmdHis') . '-' . rand(1000, 9999),
                'medicine_id' => $request->medicine_id,
                'quantity_requested' => $request->quantity_requested,
                'reason' => $request->reason,
                'expected_delivery_date' => $request->expected_delivery_date,
                'request_date' => now(),
                'requested_by' => auth()->id(),
                'status' => 'pending'
            ]);

            DB::commit();

            return redirect()->route('supply-orders.index')
                ->with('success', 'Pedido de abastecimento criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao criar pedido: ' . $e->getMessage());
        }
    }

    public function show(SupplyOrder $supplyOrder)
    {
        $supplyOrder->load(['medicine', 'requester', 'approver']);
        return view('supply-orders.show', compact('supplyOrder'));
    }

    public function edit(SupplyOrder $supplyOrder)
    {
        if (!$supplyOrder->isPending()) {
            return redirect()->route('supply-orders.index')
                ->with('error', 'Apenas pedidos pendentes podem ser editados.');
        }

        $medicines = Medicine::all();
        return view('supply-orders.edit', compact('supplyOrder', 'medicines'));
    }

    public function update(Request $request, SupplyOrder $supplyOrder)
    {
        if (!$supplyOrder->isPending()) {
            return back()->with('error', 'Apenas pedidos pendentes podem ser editados.');
        }

        $request->validate([
            'quantity_requested' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
            'expected_delivery_date' => 'nullable|date|after:today'
        ]);

        $supplyOrder->update($request->only(['quantity_requested', 'reason', 'expected_delivery_date']));

        return redirect()->route('supply-orders.index')
            ->with('success', 'Pedido atualizado com sucesso!');
    }

    public function destroy(SupplyOrder $supplyOrder)
    {
        if (!$supplyOrder->isPending()) {
            return back()->with('error', 'Apenas pedidos pendentes podem ser excluídos.');
        }

        $supplyOrder->delete();

        return redirect()->route('supply-orders.index')
            ->with('success', 'Pedido excluído com sucesso!');
    }

    // Ações administrativas
    public function approve(Request $request, SupplyOrder $supplyOrder)
    {
        $request->validate([
            'expected_delivery_date' => 'required|date|after:today'
        ]);

        $supplyOrder->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'expected_delivery_date' => $request->expected_delivery_date
        ]);

        return back()->with('success', 'Pedido aprovado com sucesso!');
    }

    public function reject(Request $request, SupplyOrder $supplyOrder)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $supplyOrder->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason
        ]);

        return back()->with('success', 'Pedido rejeitado com sucesso!');
    }

    public function receive(Request $request, SupplyOrder $supplyOrder)
    {
        $request->validate([
            'quantity_received' => 'required|integer|min:1|max:' . $supplyOrder->quantity_requested,
            'actual_delivery_date' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            $quantityReceived = $request->quantity_received;
            $supplyOrder->update([
                'quantity_received' => $quantityReceived,
                'actual_delivery_date' => $request->actual_delivery_date
            ]);

            // Atualiza o status
            if ($quantityReceived == $supplyOrder->quantity_requested) {
                $supplyOrder->update(['status' => 'completed']);
            } else {
                $supplyOrder->update(['status' => 'partial']);
            }

            // Atualiza o estoque do medicamento
            $medicine = $supplyOrder->medicine;
            $medicine->increment('stock', $quantityReceived);

            DB::commit();

            return back()->with('success', 'Recebimento registrado com sucesso! Estoque atualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao registrar recebimento: ' . $e->getMessage());
        }
    }

    // Geração automática de pedidos
    public function generateAutoOrders()
    {
        $ordersCreated = $this->stockService->createSupplyOrders();

        return back()->with('success', "{$ordersCreated} pedidos de abastecimento criados automaticamente.");
    }    
}
