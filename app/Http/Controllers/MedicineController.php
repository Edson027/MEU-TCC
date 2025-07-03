<?php

namespace App\Http\Controllers;


use App\Models\Medicine;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
class MedicineController extends Controller
{


 public function index(Request $request)
    {
        $query = Medicine::query();

        // Filtros
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('batch', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'low') {
                $query->whereColumn('stock', '<', 'minimum_stock');
            } elseif ($request->stock_status == 'normal') {
                $query->whereColumn('stock', '>=', 'minimum_stock');
            }
        }

        if ($request->filled('expiration')) {
            if ($request->expiration == 'soon') {
                $query->where('expiration_date', '<=', now()->addDays(30));
            } elseif ($request->expiration == 'expired') {
                $query->where('expiration_date', '<', now());
            }
        }

        $medicines = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $categories = Medicine::distinct('category')->pluck('category');

        return view('MEdicamento.index', compact('medicines', 'categories'));
    }

    public function create()
    {
        return view('MEdicamento.create');
    }

    public function store(Request $request)
    {
      $request->validate([
            'name' => 'required|max:255|unique:medicines,name,NULL,id,batch,'.$request->batch,
            'description' => 'nullable',
            'batch' => [
                'required',
             ],
            'expiration_date' => 'required|date|after_or_equal:today',
            'price' => 'nullable|numeric|min:0',
            'category' => 'required|max:100',
            'minimum_stock' => 'required|integer|min:1'
        ],
        [
            'expiration_date.agora_ou_depois'=>'Avalidade deve ser de hoje ou de uma data futura!',
           'name.unique'=>'já existe um medicamento com este nome e lote!',



        ]);

        $medicine = Medicine::create($request->all());

        // Registrar entrada inicial
        Movement::create([
            'medicine_id' => $medicine->id,
            'user_id' => auth()->id(),
            'type' => 'entrada',
            'quantity' => $request->stock,
            'reason' => 'Cadastro inicial',
            'movement_date' => now()
        ]);

        return redirect()->route('medicines.show', $medicine)
                         ->with('success', 'Medicamento cadastrado com sucesso!');
    }

    public function show(Medicine $medicine)
    {
        $movements = $medicine->movements()
            ->with(['user', 'request'])
            ->latest()
            ->paginate(10);

        return view('MEdicamento.show', compact('medicine', 'movements'));
    }

    public function edit(Medicine $medicine)
    {
        return view('MEdicamento.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
/*
$request->validate([
    'nome' => 'required|string|max:255|unique:medicamentos,nome,NULL,id,lote,'.$request->lote,
    'descricao' => 'nullable|string|max:500',
    'lote' => 'required|string|max:50',
    'validade' => 'required|date|after_or_equal:today',
    'quantidade_inicial' => 'required|integer|min:1|max:10000',
    'preco' => 'required|numeric|min:0.01|max:9999.99',
], [
    'validade.after_or_equal' => 'A validade deve ser hoje ou uma data futura.',
    'nome.unique' => 'Já existe um medicamento com este nome e lote.',
]);


   $request->validate([
            'name' => 'required|max:255|unique:medicines,name,NULL,id,batch,'.$request->batch,
            'description' => 'nullable',
            'batch' => [
                'required',
                Rule::unique('medicines')->ignore($medicine->id)
            ],
            'expiration_date' => 'required|date|after_or_equal:today',
            'price' => 'nullable|numeric|min:0',
            'category' => 'required|max:100',
            'minimum_stock' => 'required|integer|min:1'
        ],
        [
            'expiration_date.agora_ou_depois'=>'Avalidade deve ser de hoje ou de uma data futura!',
           'name.unique'=>'já existe um medicamento com este nome e lote!',

        ]

  */      $request->validate([
            'name' => 'required|max:255|unique:medicines,name,NULL,id,batch,'.$request->batch,
            'description' => 'nullable',
            'batch' => [
                'required',
             ],
            'expiration_date' => 'required|date|after_or_equal:today',
            'price' => 'nullable|numeric|min:0',
            'category' => 'required|max:100',
            'minimum_stock' => 'required|integer|min:1'
        ],
        [
            'expiration_date.agora_ou_depois'=>'Avalidade deve ser de hoje ou de uma data futura!',
           'name.unique'=>'já existe um medicamento com este nome e lote!',

        ]


    );

        $medicine->update($request->all());

        return redirect()->route('medicines.show', $medicine)
                         ->with('success', 'Medicamento atualizado com sucesso!');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')
                         ->with('success', 'Medicamento excluído com sucesso!');
    }

    public function history(Medicine $medicine)
    {
        $movements = $medicine->movements()
            ->with(['user', 'request'])
            ->latest()
            ->paginate(20);

        $requests = $medicine->requests()
            ->with(['user', 'responder'])
            ->latest()
            ->paginate(20);

        return view('MEdicamento.history', compact('medicine', 'movements', 'requests'));
    }

    public function restock(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|max:255',
            'movement_date' => 'required|date'
        ]);

        $medicine->increment('stock', $request->quantity);

        Movement::create([
            'medicine_id' => $medicine->id(),
            'user_id' => auth()->id(),
            'type' => 'entrada',
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'movement_date' => $request->movement_date
        ]);

        return back()->with('success', 'Estoque reabastecido com sucesso!');
    }







    /*
    public function index(Request $request)
    {
        $query = Medicine::query();

        // Filtros
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('batch', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('stock_status')) {
            $query->where('stock', $request->stock_status == 'low' ? '<' : '>=', 10);
        }

        if ($request->filled('expiration')) {
            $days = $request->expiration == 'soon' ? 30 : 90;
            $query->where('expiration_date', '<=', now()->addDays($days));
        }

        $medicines = $query->with('movements')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $categories = Medicine::distinct('category')->pluck('category');

        return view('medicines.index', compact('medicines', 'categories'));
    }

    public function create()
    {
        return view('medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'batch' => 'required|unique:medicines',
            'expiration_date' => 'required|date',
            'stock' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'category' => 'required|max:100',
            'minimum_stock' => 'required|integer|min:1'
        ]);

        $medicine = Medicine::create($request->all());

        // Registrar entrada inicial
        Movement::create([
            'medicine_id' => $medicine->id,
            'user_id' => auth()->id,
            'type' => 'entrada',
            'quantity' => $request->stock,
            'reason' => 'Cadastro inicial',
            'movement_date' => now()
        ]);

        return redirect()->route('medicines.show', $medicine)
                         ->with('success', 'Medicamento cadastrado com sucesso!');
    }

    public function show(Medicine $medicine)
    {
        $movements = $medicine->movements()
            ->with(['user', 'request'])
            ->latest()
            ->paginate(10);

        return view('medicines.show', compact('medicine', 'movements'));
    }

    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'batch' => [
                'required',
                Rule::unique('medicines')->ignore($medicine->id)
            ],
            'expiration_date' => 'required|date',
            'price' => 'nullable|numeric|min:0',
            'category' => 'required|max:100',
            'minimum_stock' => 'required|integer|min:1'
        ]);

        $medicine->update($request->all());

        return redirect()->route('medicines.show', $medicine)
                         ->with('success', 'Medicamento atualizado com sucesso!');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')
                         ->with('success', 'Medicamento excluído com sucesso!');
    }

    public function history(Medicine $medicine)
    {
        $movements = $medicine->movements()
            ->with(['user', 'request'])
            ->latest()
            ->paginate(20);

        $requests = $medicine->requests()
            ->with(['user', 'responder'])
            ->latest()
            ->paginate(20);

        return view('medicines.history', compact('medicine', 'movements', 'requests'));
    }

    public function restock(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|max:255',
            'movement_date' => 'required|date'
        ]);

        $medicine->increment('stock', $request->quantity);

        Movement::create([
            'medicine_id' => $medicine->id,
            'user_id' => auth()->id,
            'type' => 'entrada',
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'movement_date' => $request->movement_date
        ]);

        return back()->with('success', 'Estoque reabastecido com sucesso!');
    }



    /*
     public function index()
    {
        $medicines = Medicine::all();
        return view('MEdicamento.index', compact('medicines'));
    }
    public function create()
    {
        return view('MEdicamento.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'batch' => 'required',
            'expiration_date' => 'required|date',
            'stock' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0'
        ]);

        Medicine::create($request->all());

        return redirect()->route('medicines.index')
                         ->with('success', 'Medicamento cadastrado com sucesso!');
    }

    public function show(Medicine $medicine)
    {
        $movements = $medicine->movements()->latest()->paginate(10);
        return view('MEdicamento.show', compact('medicine', 'movements'));
    }

    public function edit(Medicine $medicine)
    {
        return view('MEdicamento.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|max:255',
            'batch' => 'required',
            'expiration_date' => 'required|date',
            'stock' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0'
        ]);

        $medicine->update($request->all());

        return redirect()->route('medicines.index')
                         ->with('success', 'Medicamento atualizado com sucesso!');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')
                         ->with('success', 'Medicamento excluído com sucesso!');
    }*/
}
