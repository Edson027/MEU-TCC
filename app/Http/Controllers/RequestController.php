<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Medicine;
use App\Models\Movement;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
   public function index()
    {
        $requests = Request::with(['medicine', 'user', 'responder'])
            ->filter(request(['status', 'urgency']))
            ->latest()
            ->paginate(15);

        return view('Pedidos.status', compact('requests'));
    }

     public function index2()
    {
        $requests = Request::with(['medicine', 'user', 'responder'])
            ->filter(request(['status', 'urgency']))
            ->latest()
            ->paginate(15);

        return view('Pedidos.index', compact('requests'));
    }
    public function create()
    {
        $medicines = Medicine::where('stock', '>', 0)->get();
        return view('Pedidos.create', compact('medicines'));
    }

    public function store(HttpRequest $httpRequest)
    {
        $httpRequest->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
            'urgency_level' => 'required|in:normal,urgente,muito_urgente'
        ]);

        $medicine = Medicine::find($httpRequest->medicine_id);

        Request::create([
            'user_id' => auth()->id(),
            'medicine_id' => $httpRequest->medicine_id,
            'quantity' => $httpRequest->quantity,
            'reason' => $httpRequest->reason,
            'urgency_level' => $httpRequest->urgency_level
        ]);

        return redirect()->route('requests.index')
                         ->with('success', 'Solicitação enviada com sucesso!');
    }

    public function show(Request $request)
    {
        return view('Pedidos.show', compact('request'));
    }

    public function approve(HttpRequest $httpRequest, Request $request)
    {
        $this->authorize('approve', $request);

        $httpRequest->validate([
            'response' => 'nullable|string|max:500'
        ]);

        $medicine = $request->medicine;

        // Verificar estoque
        if ($medicine->stock < $request->quantity) {
            // Atendimento parcial
            $attended = $medicine->stock;

            if ($attended > 0) {
                $medicine->decrement('stock', $attended);

                Movement::create([
                    'medicine_id' => $medicine->id,
                    'user_id' => auth()->id(),
                    'type' => 'saida',
                    'quantity' => $attended,
                    'reason' => 'Atendimento parcial: ' . $request->reason,
                    'movement_date' => now(),
                    'related_request_id' => $request->id
                ]);

                $request->update([
                    'status' => 'partial',
                    'responded_by' => auth()->id(),
                    'response' => $httpRequest->response ?? 'Atendido parcialmente (' . $attended . ' unidades)'
                ]);

                return back()->with('success', 'Solicitação atendida parcialmente!');
            }

            $request->update([
                'status' => 'rejected',
                'responded_by' => auth()->id(),
                'response' => $httpRequest->response ?? 'Estoque insuficiente'
            ]);

            return back()->with('error', 'Estoque insuficiente! Solicitação rejeitada.');
        }

        // Atendimento completo
        $medicine->decrement('stock', $request->quantity);

        Movement::create([
            'medicine_id' => $medicine->id,
            'user_id' => auth()->id(),
            'type' => 'saida',
            'quantity' => $request->quantity,
            'reason' => 'Solicitação aprovada: ' . $request->reason,
            'movement_date' => now(),
            'related_request_id' => $request->id
        ]);

        $request->update([
            'status' => 'approved',
            'responded_by' => auth()->id(),
            'response' => $httpRequest->response ?? 'Solicitação aprovada'
        ]);

        return back()->with('success', 'Solicitação aprovada com sucesso!');
    }

    public function reject(HttpRequest $httpRequest, Request $request)
    {
        $this->authorize('approve', $request);

        $httpRequest->validate([
            'response' => 'required|string|max:500'
        ]);

        $request->update([
            'status' => 'rejected',
            'responded_by' => auth()->id(),
            'response' => $httpRequest->response
        ]);

        return back()->with('success', 'Solicitação rejeitada com sucesso!');
    }


    public function edit(Request $request)
{
   // $this->authorize('update', $request);

    $medicines = Medicine::where('stock', '>', 0)->get();
    return view('Pedidos.edit', compact('request', 'medicines'));
}

public function update(HttpRequest $httpRequest, Request $request)
{
   //S $this->authorize('update', $request);

    // Não permitir edição se já foi respondido
    if ($request->status !== 'pending') {
        return back()->with('error', 'Não é possível editar um pedido já respondido.');
    }

    $httpRequest->validate([
        'medicine_id' => 'required|exists:medicines,id',
        'quantity' => 'required|integer|min:1',
        'reason' => 'required|string|max:500',
        'urgency_level' => 'required|in:normal,urgente,muito_urgente'
    ]);

    $request->update([
        'medicine_id' => $httpRequest->medicine_id,
        'quantity' => $httpRequest->quantity,
        'reason' => $httpRequest->reason,
        'urgency_level' => $httpRequest->urgency_level
    ]);

    return redirect()->route('requests.show', $request)
                     ->with('success', 'Pedido atualizado com sucesso!');
}
}
