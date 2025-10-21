<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Movement;
use Illuminate\Http\Request;

class MovimentarEstoque extends Controller
{
  public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $medicineId = $request->input('medicine_id');

        $movements = Movement::with(['medicine', 'user'])
            ->search($search)
            ->type($type)
            ->dateRange($startDate, $endDate)
            ->medicineFilter($medicineId)
            ->orderBy('movement_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $medicines = Medicine::orderBy('name')->get();
        $types = ['entrada' => 'Entrada', 'saida' => 'Saída'];

        return view('Movimentos.index', compact(
            'movements',
            'medicines',
            'types',
            'search',
            'type',
            'startDate',
            'endDate',
            'medicineId'
        ));
    }

    public function create($medicineId, $type)
    {
        $medicine = Medicine::findOrFail($medicineId);
        
        if (!in_array($type, ['entrada', 'saida'])) {
            abort(404);
        }

        return view('Movimentos.create', compact('medicine', 'type'));
    }

    public function store(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'type' => 'required|in:entrada,saida',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
            'movement_date' => 'required|date'
        ]);

        // Verificar estoque para saídas
        if ($validated['type'] === 'saida' && $medicine->stock < $validated['quantity']) {
            return back()->withErrors([
                'quantity' => 'Estoque insuficiente. Estoque atual: ' . $medicine->stock
            ])->withInput();
        }

        $validated['medicine_id'] = $medicine->id;
        $validated['user_id'] = auth()->id();

        Movement::create($validated);

        // Atualizar estoque do medicamento
        if ($validated['type'] === 'entrada') {
            $medicine->increment('stock', $validated['quantity']);
        } else {
            $medicine->decrement('stock', $validated['quantity']);
        }

        return redirect()->route('medicines.show', $medicine)
            ->with('success', 'Movimentação registrada com sucesso!');
    }

    public function show(Movement $movement)
    {
        $movement->load(['medicine', 'user']);
        return view('Movimentos.show', compact('movement'));
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $medicineId = $request->input('medicine_id');

        $movements = Movement::with(['medicine', 'user'])
            ->search($search)
            ->type($type)
            ->dateRange($startDate, $endDate)
            ->medicineFilter($medicineId)
            ->orderBy('movement_date', 'desc')
            ->get();

        return view('Movimentos.export', compact('movements'));
    }
}
