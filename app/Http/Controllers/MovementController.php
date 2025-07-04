<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class MovementController extends Controller
{
   public function create(Medicine $medicine, $type)
    {
        abort_unless(in_array($type, ['entrada', 'saida']), 404, 'Tipo de movimentação inválido');

        return view('Movimentos.create', compact('medicine', 'type'));
    }

    public function store(Request $request, Medicine $medicine)
    {
        $request->validate([
            'type' => 'required|in:entrada,saida',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|max:255',
            'movement_date' => 'required|date'
        ]);

        // Verificar estoque para saídas
        if ($request->type == 'saida' && $medicine->stock < $request->quantity) {
            return back()->withErrors([
                'quantity' => 'Estoque insuficiente! Disponível: ' . $medicine->stock
            ]);
        }

        // Atualizar estoque 
        /*
git config --global user.email "seu-email@exemplo.com"
git config --global user.name "Seu Nome"
        */
        if ($request->type == 'entrada') {
            $medicine->increment('stock', $request->quantity);
        } else {
            $medicine->decrement('stock', $request->quantity);
        }

        // Criar movimentação
        Movement::create([
            'medicine_id' => $medicine->id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'movement_date' => $request->movement_date
        ]);

        return redirect()
            ->route('medicines.show', $medicine)
            ->with('success', 'Movimentação registrada com sucesso!');
    }

    public function destroy(Movement $movement)
    {
        $medicine = $movement->medicine;

        // Reverter estoque
        if ($movement->type == 'entrada') {
            $medicine->decrement('stock', $movement->quantity);
        } else {
            $medicine->increment('stock', $movement->quantity);
        }

        $movement->delete();

        return back()
            ->with('success', 'Movimentação revertida e excluída!');
    }
}
