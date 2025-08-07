<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\RestockOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RestockOrderController extends Controller
{
     // Listar todos os pedidos
    public function index()
    {
        $orders = RestockOrder::with(['product', 'requester', 'processor'])
            ->latest()
            ->paginate(10);
        $medicine=Medicine::all();

        $stats = [
        'pending' => RestockOrder::where('status', 'pending')->count(),
        'approved' => RestockOrder::where('status', 'approved')->count(),
        'rejected' => RestockOrder::where('status', 'rejected')->count(),
        'total' => RestockOrder::count(),
    ];
        return view('restock-orders.index', compact('orders','medicine','stats'));
    } 
 
    public function create(){
                $medicine=Medicine::all();
  $stats = [
        'pending' => RestockOrder::where('status', 'pending')->count(),
        'approved' => RestockOrder::where('status', 'approved')->count(),
        'rejected' => RestockOrder::where('status', 'rejected')->count(),
        'total' => RestockOrder::count(),
    ];
      $orders = RestockOrder::with(['product', 'requester', 'processor'])
            ->latest()
            ->paginate(10);
      
        return view('restock-orders.create',compact('medicine','stats','orders'));
    }
    // Criar novo pedido
    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:products,id',
            'quantity_requested' => 'required|integer|min:1'
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);

        RestockOrder::create([
            'medicine_id' => $medicine->id,
            'quantity_requested' => $request->quantity_requested,
            'requested_by' => Auth::id(),
            'status' => RestockOrder::STATUS_PENDING
        ]);

//        return redirect()->back()->with('success', 'Pedido de reabastecimento criado com sucesso!');
return redirect()->route('medicines.show', $medicine)
                         ->with('success','Pedido de reabastecimento criado com sucesso!');
        

    }

    // Aprovar pedido
    public function approve(RestockOrder $order)
    {
        if (!$order->isPending()) {
            return redirect()->back()->with('error', 'Este pedido já foi processado.');
        }

        $order->approve(Auth::user());

        return redirect()->back()->with('success', 'Pedido aprovado e estoque atualizado!');
    }

    // Rejeitar pedido
    public function reject(Request $request, RestockOrder $order)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        if (!$order->isPending()) {
            return redirect()->back()->with('error', 'Este pedido já foi processado.');
        }

        $order->reject(Auth::user(), $request->rejection_reason);

        return redirect()->back()->with('success', 'Pedido rejeitado com sucesso.');
    }
}
