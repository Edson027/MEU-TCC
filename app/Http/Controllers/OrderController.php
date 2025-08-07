<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   public function index()
    {
        $orders = Order::with(['medicine', 'requester'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('Order.index', compact('orders'));
    }

    public function create()
    {
        $medicines = Medicine::where('stock', '>', 0)
            ->orderBy('name')
            ->get();
            
        $urgencyLevels = [
            Order::URGENCY_LOW => 'Baixa',
            Order::URGENCY_MEDIUM => 'Média',
            Order::URGENCY_HIGH => 'Alta'
        ];
        
        return view('Order.create', compact('medicines', 'urgencyLevels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'urgency_level' => 'required|in:low,medium,high',
            'notes' => 'nullable|string|max:500'
        ]);

        $medicine = Medicine::findOrFail($validated['medicine_id']);
        
        // Verifica se há estoque suficiente
        if ($medicine->stock < $validated['quantity']) {
            return back()->withInput()->withErrors([
                'quantity' => 'Quantidade solicitada maior que o estoque disponível.'
            ]);
        }

        $order = new Order();
        $order->medicine_id = $validated['medicine_id'];
        $order->quantity = $validated['quantity'];
        $order->urgency_level = $validated['urgency_level'];
        $order->notes = $validated['notes'];
        $order->status = Order::STATUS_PENDING;
        $order->requested_by = auth()->id();
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Pedido criado com sucesso!');
    }

    public function show(Order $order)
    {
        return view('Order.show', compact('order'));
    }

    public function edit(Order $order)
    {
        if ($order->status !== Order::STATUS_PENDING) {
            return redirect()->route('orders.index')->with('error', 'Só é possível editar pedidos pendentes.');
        }

        $medicines = Medicine::orderBy('name')->get();
        $urgencyLevels = [
            Order::URGENCY_LOW => 'Baixa',
            Order::URGENCY_MEDIUM => 'Média',
            Order::URGENCY_HIGH => 'Alta'
        ];
        
        return view('Order.edit', compact('order', 'medicines', 'urgencyLevels'));
    }

    public function update(Request $request, Order $order)
    {
        if ($order->status !== Order::STATUS_PENDING) {
            return redirect()->route('orders.index')->with('error', 'Só é possível editar pedidos pendentes.');
        }

        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'urgency_level' => 'required|in:low,medium,high',
            'notes' => 'nullable|string|max:500'
        ]);

        $medicine = Medicine::findOrFail($validated['medicine_id']);
        
        if ($medicine->stock < $validated['quantity']) {
            return back()->withInput()->withErrors([
                'quantity' => 'Quantidade solicitada maior que o estoque disponível.'
            ]);
        }

        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Pedido atualizado com sucesso!');
    }

    public function destroy(Order $order)
    {
        if ($order->status !== Order::STATUS_PENDING) {
            return redirect()->route('Order.index')->with('error', 'Só é possível cancelar pedidos pendentes.');
        }

        $order->status = Order::STATUS_REJECTED;
        $order->approved_by = auth()->id();
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Pedido cancelado com sucesso!');
    }

    public function approve(Order $order)
    {
        if ($order->status !== Order::STATUS_PENDING) {
            return redirect()->route('orders.index')->with('error', 'Só é possível aprovar pedidos pendentes.');
        }

        $medicine = $order->medicine;
        
        if ($medicine->stock < $order->quantity) {
            return redirect()->route('orders.index')->with('error', 'Estoque insuficiente para aprovar o pedido.');
        }

        // Atualiza o estoque
        $medicine->stock -= $order->quantity;
        $medicine->save();

        // Atualiza o pedido
        $order->status = Order::STATUS_APPROVED;
        $order->approved_by = auth()->id();
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Pedido aprovado e estoque atualizado!');
    }
}
