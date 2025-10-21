<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\Medicine;
use App\Models\Movement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Role;
class MedicineController extends Controller
{


 public function index(Request $request)
    {
        //$query = Medicine::query();
$query = Medicine::query()->with('fornecedor'); 
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
    } elseif ($request->stock_status == 'out') {
        $query->where('stock', 0);
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

                    $summary = [
            'total_medicines' => Medicine::count(),
            'low_stock' => Medicine::whereColumn('stock', '<', 'minimum_stock')->count(),
            'expiring_soon' => Medicine::where('expiration_date', '<=', now()->addDays(30))->count(),
            'total_users' => User::count(),
           'expired_medicines' => Medicine::where('expiration_date', '<', now())->count(),
              'roles'=>Role::All()
          //  'completed_prescriptions' => Prescription::where('status', 'completed')->count()
        ];

        $categories = Medicine::distinct('category')->pluck('category');


        return view('MEdicamento.index', compact('medicines', 'categories','summary'));
    }

    public function create()
    {
          $fornecedor=Fornecedor::all();
        return view('MEdicamento.create', compact('fornecedor'));
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
            'minimum_stock' => 'required|integer|min:1',
              'fornecedor_id' => 'required|integer|exists:fornecedors,id'
        ],
        [
            'expiration_date.agora_ou_depois'=>'Avalidade deve ser de hoje ou de uma data futura!',
           'name.unique'=>'já existe um medicamento com este nome e lote!',



        ],);
   //dd($request->all()); // Para testar se está chegando
   $medicine = Medicine::create($request->all());

        // Registrar entrada inicial
        Movement::create([
            'medicine_id' => $medicine->id,
            'user_id' => auth()->id(),
            'type' => 'entrada',
            'quantity' => $request->stock,
            'reason' => 'Cadastro inicial do medicamneto',
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
  /*      
        $movements = $medicine->movements()
            ->with(['user', 'request'])
            ->latest()
            ->paginate(10);
*/

$movements = $medicine->movements()
    ->with([
        'user:id,name',
        'request:id,code',
        'medicine.fornecedor:id,nome' // Agora deve funcionar
    ])
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

      protected function checkLowStock2()
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

   protected function checkExpiringSoon2()
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

     protected function createNotification2($userId, $medicineId, $type, $message)
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

    public function VerificarEstadoP()
{
    $critical_stock = Medicine::where('stock', '>', 0)
        ->whereColumn('stock', '<', 'minimum_stock')
        ->get();
    
    $expiring_soon = Medicine::where('expiration_date', '<=', now()->addDays(45))
        ->where('expiration_date', '>', now())
        ->get();
    
    $zero_stock = Medicine::where('stock', 0)->get();
    
    return response()->json([
        'critical_stock' => $critical_stock,
        'expiring_soon' => $expiring_soon,
        'zero_stock' => $zero_stock
    ]);
}

    
public function notifications2()
{
    $notifications = auth()->user()->notifications()
        ->with('medicine')
        ->latest()
        ->paginate(10);
 
    return view('Notificar.index', compact('notifications'));
}

public function markAsRead2(Notification $notification)
{
    $this->authorize('update', $notification);
    
    $notification->update(['read' => true]);
    
    return back()->with('success', 'Notificação marcada como lida');
}

public function deleteNotification2(Notification $notification)
{
    $this->authorize('delete', $notification);
    
    $notification->delete();
    
    return back()->with('success', 'Notificação removida');
}


public function dashboard()
{
    // Estatísticas básicas
    $totalMedicines = Medicine::count();
    $lowStockCount = Medicine::whereColumn('stock', '<', 'minimum_stock')->count();
    $expiringSoonCount = Medicine::where('expiration_date', '<=', now()->addDays(30))
                                ->where('expiration_date', '>', now())
                                ->count();
    $expiredCount = Medicine::where('expiration_date', '<', now())->count();
    
    // Medicamentos com estoque baixo
    $lowStockMedicines = Medicine::whereColumn('stock', '<', 'minimum_stock')
                                ->orderBy('stock', 'asc')
                                ->limit(5)
                                ->get();
    
    // Medicamentos próximos a vencer
    $expiringSoonMedicines = Medicine::where('expiration_date', '<=', now()->addDays(30))
                                    ->orderBy('expiration_date', 'asc')
                                    ->limit(5)
                                    ->get();
     
    // Notificações
    $recentNotifications = Notification::with('medicine')
                                    ->latest()
                                    ->limit(5)
                                    ->get();
    $unreadCount = Notification::where('read', false)->count();
    
    // Categorias para o gráfico
    $categories = Medicine::select('category', DB::raw('count(*) as count'))
                        ->groupBy('category')
                        ->get();
    
    // Cores para as categorias
    $categoryColors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
        '#5a5c69', '#858796', '#b7b7b7', '#24d5e7', '#f8f9fc'
    ];
    
    // Movimentações dos últimos 7 dias
    $movementDays = Movement::select(
            DB::raw('DATE(movement_date) as date'),
            DB::raw('SUM(CASE WHEN type = "entrada" THEN quantity ELSE 0 END) as entradas'),
            DB::raw('SUM(CASE WHEN type = "saida" THEN quantity ELSE 0 END) as saidas'),
            DB::raw("DATE_FORMAT(DATE(movement_date), '%d/%m') as date_formatted")
        )
        ->where('movement_date', '>=', now()->subDays(7))
        ->groupBy('date', 'date_formatted')
        ->orderBy('date')
        ->get();
    
    return view('Administrador.index', compact(
        'totalMedicines',
        'lowStockCount',
        'expiringSoonCount',
        'expiredCount',
        'lowStockMedicines',
        'expiringSoonMedicines',
        'recentNotifications',
        'unreadCount',
        'categories',
        'categoryColors',
        'movementDays'
    ));
}

/**
 * Verifica e notifica sobre estoque baixo e validade próxima
 * Pode ser chamado por tarefa agendada ou manualmente
 */
public function checkStockAndValidity()
{
    $this->checkLowStock();
    $this->checkExpiringSoon();
    
    return back()->with('success', 'Verificação de estoque e validade realizada com sucesso!');
}

/**
 * Verifica medicamentos com estoque abaixo do mínimo
 */
protected function checkLowStock()
{
    $medicines = Medicine::whereColumn('stock', '<', 'minimum_stock')->get();
    
    foreach ($medicines as $medicine) {
        $message = "O medicamento {$medicine->name} (Lote: {$medicine->batch}) está com estoque baixo. "
                  ."Quantidade atual: {$medicine->stock}, Mínimo: {$medicine->minimum_stock}";
        
        $this->createStockNotification($medicine, $message);
    }
}

/**
 * Verifica medicamentos próximos da validade
 */
protected function checkExpiringSoon()
{
    $threshold = now()->addDays(30); // 30 dias para expirar
    $medicines = Medicine::where('expiration_date', '<=', $threshold)->get();
    
    foreach ($medicines as $medicine) {
        $expirationDate = Carbon::parse($medicine->expiration_date)->format('d/m/Y');
        $daysRemaining = now()->diffInDays($medicine->expiration_date);
        
        $message = "O medicamento {$medicine->name} (Lote: {$medicine->batch}) está próximo da data de expiração. "
                  ."Validade: {$expirationDate} ({$daysRemaining} dias restantes). "
                  ."Quantidade em estoque: {$medicine->stock}";
        
        $this->createExpirationNotification($medicine, $message);
    }
}

/**
 * Cria notificação de estoque baixo
 */
protected function createStockNotification(Medicine $medicine, string $message)
{
    // Notificar administradores
    $admins = User::where('is_admin', true)->get();
    
    foreach ($admins as $admin) {
        $this->createNotification($admin->id, $medicine->id, 'stock', $message);
    }
    
    // Notificar usuários com permissão específica (se aplicável)
    // $usersWithPermission = User::permission('gerenciar.estoque')->get();
    // foreach ($usersWithPermission as $user) {
    //     $this->createNotification($user->id, $medicine->id, 'stock', $message);
    // }
}

/**
 * Cria notificação de validade próxima
 */
protected function createExpirationNotification(Medicine $medicine, string $message)
{
    // Notificar administradores
    $admins = User::where('is_admin', true)->get();
    
    foreach ($admins as $admin) {
        $this->createNotification($admin->id, $medicine->id, 'expiration', $message);
    }
    
    // Notificar farmacêuticos/responsáveis (se aplicável)
    // $pharmacists = User::role('farmaceutico')->get();
    // foreach ($pharmacists as $pharmacist) {
    //     $this->createNotification($pharmacist->id, $medicine->id, 'expiration', $message);
    // }
}

/**
 * Cria a notificação no banco de dados
 */
protected function createNotification(int $userId, int $medicineId, string $type, string $message)
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
        
        // Aqui você pode adicionar notificação por e-mail se necessário
        // $user = User::find($userId);
        // Mail::to($user->email)->send(new StockNotification($message));
    }
}

public function notifications(Request $request)
{
    $query = auth()->user()->notifications()
                ->with('medicine')
                ->latest();
    
    // Filtros
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }
    
    if ($request->filled('status')) {
        $query->where('read', $request->status == 'read');
    }
    
    if ($request->filled('date')) {
        switch ($request->date) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
        }
    }
    
    $notifications = $query->paginate(15);
    
    return view('Notificacoes.index', compact('notifications'));
}

public function markAsRead(Notification $notification)
{
    $this->authorize('update', $notification);
    
    $notification->update(['read' => true]);
    
    return back()->with('success', 'Notificação marcada como lida');
}

public function markAllAsRead()
{
    auth()->user()->notifications()
        ->where('read', false)
        ->update(['read' => true]);
    
    return back()->with('success', 'Todas as notificações foram marcadas como lidas');
}

public function deleteNotification(Notification $notification)
{
    $this->authorize('delete', $notification);
    
    $notification->delete();
    
    return back()->with('success', 'Notificação removida com sucesso');
}

public function clearNotifications()
{
    auth()->user()->notifications()->delete();
    
    return back()->with('success', 'Todas as notificações foram removidas');
}




 public function administrador()
    {
        // Estatísticas principais
        $stats = [
            'total_medicines' => Medicine::count(),
            'low_stock' => Medicine::whereColumn('stock', '<', 'minimum_stock')->count(),
            'expiring_soon' => Medicine::where('expiration_date', '<=', now()->addDays(30))->count(),
            'total_users' => User::count(),
           // 'new_prescriptions' => Prescription::whereDate('created_at', today())->count(),
            'roles'=>Role::All()
          //  'completed_prescriptions' => Prescription::where('status', 'completed')->count()
        ];

 $total = DB::table('medicines')->sum('stock');       

        // Gráfico de movimentações (últimos 30 dias)
        $movementData = Movement::selectRaw('
            DATE(updated_at) as date,
            SUM(CASE WHEN type = "entrada" THEN quantity ELSE 0 END) as entradas,
            SUM(CASE WHEN type = "saida" THEN quantity ELSE 0 END) as saidas
        ')
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();


// Soma total de ENTRADAS (type = 'entrada')
$totalEntradas = Movement::where('type', 'entrada')->sum('quantity');

// Soma total de SAÍDAS (type = 'saida')
$totalSaidas = Movement::where('type', 'saida')->sum('quantity');

// Saldo atual (Entradas - Saídas)
$saldo = $totalEntradas - $totalSaidas;

//$resultaso=$movementData-$total;
        // Últimas atividades
        $activities = [
            //  $deletedMedicines = Medicine::onlyTrashed()->count();
    //$allMedicines = Medicine::withTrashed()->get();
            'medicines' => Medicine::withTrashed()->latest()->take(5)->get(),
            'users' => User::latest()->take(5)->get(),
         //   'prescriptions' => Prescription::latest()->take(5)->get()
        ];

        // Alertas críticos
        $alerts = [
            'stock_out' => Medicine::where('stock', 0)->count(),
            'expired' => Medicine::where('expiration_date', '<', now())->count(),
             'stock_out_impact' =>Medicine::where('stock', 0)->sum('minimum_stock'),

        ];



        return view('PainelAdministrativo', compact('stats', 'movementData', 'activities', 'alerts','total','totalEntradas','totalSaidas','saldo'));
    }
  
}
