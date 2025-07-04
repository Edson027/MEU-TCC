<?php

namespace App\Http\Controllers;


use App\Models\Medicine;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Notification;
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

        // Verificar se o estoque está abaixo do mínimo
        if ($medicine->stock < $medicine->minimum_stock) {
            $this->checkLowStock();
        }

        return redirect()->route('medicines.show', $medicine)
                         ->with('success', 'Medicamento cadastrado com sucesso!');
        /*

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
                         ->with('success', 'Medicamento cadastrado com sucesso!');*/
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
    $request->validate([
            'name' => 'required|max:255|unique:medicines,name,NULL,id,batch,'.$request->batch,
            'description' => 'nullable',
            'batch' => [
                'required',
             ],
            'expiration_date' => 'required|date|after_or_equal:today',
         
            'category' => 'required|max:100',
            'minimum_stock' => 'required|integer|min:1'
        ],
        [
            'expiration_date.agora_ou_depois'=>'Avalidade deve ser de hoje ou de uma data futura!',
           'name.unique'=>'já existe um medicamento com este nome e lote!',

        ]


    );

       $medicine->update($request->all());

        // Verificar se o estoque está abaixo do mínimo
        if ($medicine->stock < $medicine->minimum_stock) {
            $this->checkLowStock();
        }

        return redirect()->route('medicines.show', $medicine)
                         ->with('success', 'Medicamento atualizado com sucesso!');
    }
/*
        $medicine->update($request->all());

        return redirect()->route('medicines.show', $medicine)
                         ->with('success', 'Medicamento atualizado com sucesso!');
    }*/

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

      public function checkStockAndExpiration()
    {
        $this->checkLowStock();
        $this->checkExpiringSoon();
    }

      protected function checkLowStock()
    {
        $medicines = Medicine::whereColumn('stock', '<', 'minimum_stock')->get();
        
        foreach ($medicines as $medicine) {
            $message = "O medicamento {$medicine->name} (Lote: {$medicine->batch}) está com estoque baixo. Quantidade atual: {$medicine->stock}, Mínimo: {$medicine->minimum_stock}";
            
            // Notificar administradores
            $admins = User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                $this->createNotification($admin->id, $medicine->id, 'stock', $message);
            }
            
            // Notificar usuário logado (se houver)
            if (auth()->check()) {
                $this->createNotification(auth()->id(), $medicine->id, 'stock', $message);
            }
        }
    }

   protected function checkExpiringSoon()
    {
        $threshold = now()->addDays(30);
        $medicines = Medicine::where('expiration_date', '<=', $threshold)->get();
        
        foreach ($medicines as $medicine) {
            $expirationDate = Carbon::parse($medicine->expiration_date)->format('d/m/Y');
            $message = "O medicamento {$medicine->name} (Lote: {$medicine->batch}) está próximo da data de expiração ({$expirationDate}). Quantidade em estoque: {$medicine->stock}";
            
            // Notificar administradores
            $admins = User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                $this->createNotification($admin->id, $medicine->id, 'expiration', $message);
            }
            
            // Notificar usuário logado (se houver)
            if (auth()->check()) {
                $this->createNotification(auth()->id(), $medicine->id, 'expiration', $message);
            }
        }
    }

     protected function createNotification($userId, $medicineId, $type, $message)
    {
        // Verifica se já existe notificação não lida para evitar duplicatas
        $exists = Notification::where('user_id', $userId)
            ->where('medicine_id', $medicineId)
            ->where('type', $type)
            ->where('read', false)
            ->exists();
            
        if (!$exists) {
            Notification::create([
                'user_id' => $userId,
                'medicine_id' => $medicineId,
                'type' => $type,
                'message' => $message
            ]);
        }
    }
    
//resources\views\Notificar
public function notifications()
{
    $notifications = auth()->user()->notifications()
        ->with('medicine')
        ->latest()
        ->paginate(10);
 
    return view('Notificar.index', compact('notifications'));
}

public function markAsRead(Notification $notification)
{
    $this->authorize('update', $notification);
    
    $notification->update(['read' => true]);
    
    return back()->with('success', 'Notificação marcada como lida');
}

public function deleteNotification(Notification $notification)
{
    $this->authorize('delete', $notification);
    
    $notification->delete();
    
    return back()->with('success', 'Notificação removida');
}





  
}
