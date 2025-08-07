<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\StockAlert;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Events\LowStockEvent;
use App\Models\User;
use App\Notifications\LowStockAlert;
use App\Notifications\RestockRequested;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\StockAlertApproved;
use App\Notifications\StockAlertRejected;
class StockAlertController extends Controller
{
  public function index()
    {
        // Estatísticas gerais
        $stats = $this->getStockStats();
        
        // Medicamentos com estoque crítico (abaixo de 20% do mínimo)
        $criticalAlerts = Medicine::whereColumn('stock', '<', DB::raw('minimum_stock * 0.2'))
            ->orderByRaw('stock / minimum_stock ASC')
            ->limit(10)
            ->get();
        
        // Notificações recentes
        $notifications = Notification::where('type', LowStockAlert::class)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Medicamentos para a tabela principal
        $medicines = Medicine::query()
            ->when(request('critical_level'), function($query, $level) {
                return match($level) {
                    'critical' => $query->whereColumn('stock', '<', DB::raw('minimum_stock * 0.2')),
                    'low' => $query->whereColumn('stock', '<', DB::raw('minimum_stock')),
                    'normal' => $query->whereColumn('stock', '>=', DB::raw('minimum_stock')),
                    'out' => $query->where('stock', '<=', 0),
                    default => $query
                };
            })
            ->when(request('sort'), function($query, $sort) {
                $order = request('order', 'asc');
                return $query->orderBy($sort, $order);
            }, function($query) {
                return $query->orderByRaw('stock / minimum_stock ASC');
            })
            ->paginate(15);
        
        return view('alerta.index', compact(
            'stats',
            'criticalAlerts',
            'notifications',
            'medicines'
        ));
    }

    public function requestRestock(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $medicine = Medicine::findOrFail($request->medicine_id);
        
        // Cria registro da solicitação
        $alert = StockAlert::create([
            'medicine_id' => $medicine->id,
            'requested_by' => Auth::id(),
            'quantity' => $request->quantity,
            'priority' => $request->priority,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);


        
        // Dispara notificação
        $usersToNotify = User::whereIn('role', ['admin', 'purchasing'])
            ->get();
            
        Notification::send($usersToNotify, new RestockRequested($alert));
        
        return response()->json([
            'success' => true,
            'message' => 'Solicitação de reposição enviada com sucesso!'
        ]);
    }

    public function exportar(){}

    /**
     * Envia notificações para medicamentos com estoque baixo
     */
    public function sendLowStockNotifications()
    {
        $medicines = Medicine::whereColumn('stock', '<', 'minimum_stock')
            ->where(function($query) {
                $query->whereNull('last_alerted_at')
                    ->orWhere('last_alerted_at', '<', now()->subHours(24));
            })
            ->get();
        
        $usersToNotify = User::whereIn('role', ['admin', 'manager'])
            ->get();
        
        foreach ($medicines as $medicine) {
            // Dispara evento para atualização em tempo real
            event(new LowStockEvent($medicine));
            
            // Envia notificações
            Notification::send($usersToNotify, new LowStockAlert($medicine));
            
            // Atualiza último alerta
            $medicine->update(['last_alerted_at' => now()]);
        }
        
        return back()->with('success', 'Notificações enviadas para ' . $medicines->count() . ' medicamentos');
    }

    /**
     * Calcula estatísticas de estoque
     */
    protected function getStockStats()
    {
        $totalMedicines = Medicine::count();
        $criticalStock = Medicine::whereColumn('stock', '<', DB::raw('minimum_stock * 0.2'))->count();
        $lowStock = Medicine::whereColumn('stock', '<', DB::raw('minimum_stock'))->count() - $criticalStock;
        $normalStock = Medicine::whereColumn('stock', '>=', DB::raw('minimum_stock'))->count();
        $outOfStock = Medicine::where('stock', '<=', 0)->count();
        
        return [
            'total_medicines' => $totalMedicines,
            'critical_stock' => $criticalStock,
            'low_stock' => $lowStock,
            'normal_stock' => $normalStock,
            'out_of_stock' => $outOfStock,
            'critical_percentage' => $totalMedicines > 0 ? round(($criticalStock / $totalMedicines) * 100, 1) : 0,
            'low_percentage' => $totalMedicines > 0 ? round(($lowStock / $totalMedicines) * 100, 1) : 0,
            'normal_percentage' => $totalMedicines > 0 ? round(($normalStock / $totalMedicines) * 100, 1) : 0,
        ];
    }

      public function create()
    {
        $medicines = Medicine::whereColumn('stock', '<', 'minimum_stock')
            ->orderBy('name')
            ->get();

        return view('stock-alerts.create', compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string|max:500'
        ]);

        $alert = StockAlert::create([
            'medicine_id' => $validated['medicine_id'],
            'requested_by' => Auth::id(),
            'quantity' => $validated['quantity'],
            'priority' => $validated['priority'],
            'notes' => $validated['notes'],
            'status' => StockAlert::STATUS_PENDING
        ]);

        // Notificar responsáveis
        $usersToNotify = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'purchasing']);
        })->get();

        Notification::send($usersToNotify, new RestockRequested($alert));

        return redirect()->route('stock-alerts.index')
            ->with('success', 'Solicitação de reposição criada com sucesso!');
    }

    /**
     * Process a stock alert request.
     */
    public function process(StockAlert $stockAlert)
    {
        if ($stockAlert->status != StockAlert::STATUS_PENDING) {
            return back()->with('error', 'Esta solicitação já está sendo processada ou foi concluída');
        }

        $stockAlert->update([
            'status' => StockAlert::STATUS_PROCESSING,
            'processed_by' => Auth::id(),
            'processed_at' => now()
        ]);

        return back()->with('success', 'Solicitação marcada como em processamento');
    }

    /**
     * Complete a stock alert request.
     */
    public function complete(StockAlert $stockAlert)
    {
        if ($stockAlert->status != StockAlert::STATUS_PROCESSING) {
            return back()->with('error', 'Apenas solicitações em processamento podem ser concluídas');
        }

        DB::transaction(function() use ($stockAlert) {
            // Atualiza o estoque
            $stockAlert->medicine->increment('stock', $stockAlert->quantity);
            
            // Atualiza o status
            $stockAlert->update([
                'status' => StockAlert::STATUS_COMPLETED
            ]);
        });

        return back()->with('success', 'Reposição concluída e estoque atualizado');
    }

    /**
     * Cancel a stock alert request.
     */
    public function cancel(StockAlert $stockAlert)
    {
        if (!in_array($stockAlert->status, [StockAlert::STATUS_PENDING, StockAlert::STATUS_PROCESSING])) {
            return back()->with('error', 'Esta solicitação não pode ser cancelada');
        }

        $stockAlert->update([
            'status' => StockAlert::STATUS_CANCELLED
        ]);

        return back()->with('success', 'Solicitação cancelada com sucesso');
    }

    /**
     * Check for low stock and send notifications.
     */
    public function checkLowStock()
    {
        $medicines = Medicine::whereColumn('stock', '<', 'minimum_stock')
            ->where(function($query) {
                $query->whereNull('last_alerted_at')
                    ->orWhere('last_alerted_at', '<', now()->subHours(24));
            })
            ->get();

        $usersToNotify = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'manager']);
        })->get();

        foreach ($medicines as $medicine) {
            event(new LowStockEvent($medicine));
            Notification::send($usersToNotify, new LowStockAlert($medicine));
            $medicine->update(['last_alerted_at' => now()]);
        }

        return back()->with('success', 'Verificação de estoque concluída. Notificações enviadas: ' . $medicines->count());
    }

 public function approve(Request $request, StockAlert $stockAlert)
    {
        if (!$stockAlert->canBeApproved()) {
            return back()->with('error', 'Este pedido não pode ser aprovado no momento.');
        }

        $validated = $request->validate([
            'approved_quantity' => 'required|integer|min:1|max:' . $stockAlert->quantity,
            'approval_notes' => 'nullable|string|max:500'
        ]);

        $stockAlert->update([
            'status' => StockAlert::STATUS_APPROVED,
            'approved_quantity' => $validated['approved_quantity'],
            'approval_notes' => $validated['approval_notes'],
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        // Notificar o solicitante
        $stockAlert->requester->notify(new StockAlertApproved($stockAlert));

        return back()->with('success', 'Pedido aprovado com sucesso!');
    }

    /**
     * Rejeitar um pedido de alta prioridade
     */
    public function reject(Request $request, StockAlert $stockAlert)
    {
        if (!$stockAlert->canBeRejected()) {
            return back()->with('error', 'Este pedido não pode ser rejeitado no momento.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $stockAlert->update([
            'status' => StockAlert::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_by' => Auth::id(),
            'rejected_at' => now()
        ]);
        

        // Notificar o solicitante
        $stockAlert->requester->notify(new StockAlertRejected($stockAlert));

        return back()->with('success', 'Pedido rejeitado com sucesso!');
    }

    /**
     * Retornar um pedido para pendente
     */
    public function returnToPending(StockAlert $stockAlert)
    {
        if (!in_array($stockAlert->status, [StockAlert::STATUS_APPROVED, StockAlert::STATUS_REJECTED])) {
            return back()->with('error', 'Apenas pedidos aprovados ou rejeitados podem ser retornados para pendente.');
        }

        $stockAlert->update([
            'status' => StockAlert::STATUS_PENDING,
            'approved_quantity' => null,
            'approval_notes' => null,
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => null,
            'rejected_by' => null,
            'rejected_at' => null
        ]);

        return back()->with('success', 'Pedido retornado para pendente com sucesso!');
    }

    /**
     * Lista de pedidos para aprovação
     */
    public function approvalList()
    {
        $alerts = StockAlert::needsApproval()
            ->with(['medicine', 'requester'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('stock-alerts.approval-list', compact('alerts'));
    }

    /**
     * Marca notificação como lida
     */
    /*public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }*/

}
