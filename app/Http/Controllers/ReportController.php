<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Medicine;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

use App\Models\Request as MedicineRequest; // Já está no seu código
use Illuminate\Http\Request; // Adicione esta linha
class ReportController extends Controller
{
  public function index()
    {
        return view('reports.index');
    }

    public function stockReport(Request $request)
    {
        $criticalStock = Medicine::whereColumn('stock', '<', 'minimum_stock')
            ->orderBy('stock')
            ->get();

        $expiringSoon = Medicine::where('expiration_date', '<=', now()->addDays(30))
            ->orderBy('expiration_date')
            ->get();

        $lowRotation = Medicine::withCount(['movements' => function($query) {
            $query->where('type', 'saida')
                  ->where('movement_date', '>=', now()->subYear());
        }])
        ->having('movements_count', '<', 5)
        ->orderBy('movements_count')
        ->get();

        if ($request->has('pdf')) {
            $pdf = Pdf::loadView('reports.pdf.stock', compact('criticalStock', 'expiringSoon', 'lowRotation'));
            return $pdf->download('relatorio_estoque_'.now()->format('Y-m-d').'.pdf');
        }

        return view('reports.stock', compact('criticalStock', 'expiringSoon', 'lowRotation'));
    }
/*

public function consumptionReport(Request $request)
{
    $period = $request->input('period', 'month');

    $endDate = now();
    switch ($period) {
        case 'week': $startDate = now()->subWeek(); break;
        case 'year': $startDate = now()->subYear(); break;
        default: $startDate = now()->subMonth();
    }

    // Medicamentos mais consumidos (agora funciona com dateRange)
    $topConsumed = Movement::with('medicine')
        ->outgoing() // Filtra apenas saídas
        ->dateRange($startDate, $endDate) // Filtra por intervalo de datas
        ->selectRaw('medicine_id, sum(quantity) as total')
        ->groupBy('medicine_id')
        ->orderByDesc('total')
        ->take(10)
        ->get();

    // Consumo por categoria (também corrigido)
    $categories = Medicine::join('movements', 'medicines.id', '=', 'movements.medicine_id')
        ->selectRaw('medicines.category, sum(movements.quantity) as total')
        ->where('movements.type', 'saida')
        ->whereBetween('movements.movement_date', [$startDate, $endDate]) // Alternativa sem escopo
        ->groupBy('medicines.category')
        ->orderByDesc('total')
        ->get();

    // Consumo mensal (últimos 12 meses)
    $monthlyConsumption = Movement::outgoing()
        ->selectRaw("DATE_FORMAT(movement_date, '%Y-%m') as month, sum(quantity) as total")
        ->where('movement_date', '>=', now()->subYear())
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    if ($request->has('pdf')) {
        $pdf = Pdf::loadView('reports.pdf.consumption', [
            'topConsumed' => $topConsumed,
            'categories' => $categories,
            'monthlyConsumption' => $monthlyConsumption,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $period
        ]);
        return $pdf->download('relatorio_consumo_'.now()->format('Y-m-d').'.pdf');
    }

    return view('reports.consumption', compact(
        'topConsumed', 'categories', 'monthlyConsumption', 'startDate', 'endDate', 'period'
    ));
}
*//*
    public function consumptionReport(Request $request)
    {
        $period = $request->input('period', 'month');

        $endDate = now();
        switch ($period) {
            case 'week': $startDate = now()->subWeek(); break;
            case 'year': $startDate = now()->subYear(); break;
            default: $startDate = now()->subMonth();
        }

        // Medicamentos mais consumidos
        $topConsumed = Movement::with('medicine')
            ->outgoing()
            ->scopeDateRange($startDate, $endDate)
            ->selectRaw('medicine_id, sum(quantity) as total')
            ->groupBy('medicine_id')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        // Consumo por categoria
        $categories = Medicine::join('movements', 'medicines.id', '=', 'movements.medicine_id')
            ->selectRaw('medicines.category, sum(movements.quantity) as total')
            ->where('movements.type', 'saida')
            ->dateRange($startDate, $endDate)
            ->groupBy('medicines.category')
            ->orderByDesc('total')
            ->get();

        // Consumo mensal (últimos 12 meses)
        $monthlyConsumption = Movement::outgoing()
            ->selectRaw("DATE_FORMAT(movement_date, '%Y-%m') as month, sum(quantity) as total")
            ->where('movement_date', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        if ($request->has('pdf')) {
            $pdf = Pdf::loadView('reports.pdf.consumption', [
                'topConsumed' => $topConsumed,
                'categories' => $categories,
                'monthlyConsumption' => $monthlyConsumption,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'period' => $period
            ]);
            return $pdf->download('relatorio_consumo_'.now()->format('Y-m-d').'.pdf');
        }

        return view('reports.consumption', compact(
            'topConsumed', 'categories', 'monthlyConsumption', 'startDate', 'endDate', 'period'
        ));
    }
*/

public function consumptionReport(Request $request)
{
    $period = $request->input('period', 'month');

    $endDate = now();
    switch ($period) {
        case 'week': $startDate = now()->subWeek(); break;
        case 'year': $startDate = now()->subYear(); break;
        default: $startDate = now()->subMonth();
    }

    // Medicamentos mais consumidos (agora funciona com dateRange)
    $topConsumed = Movement::with('medicine')
        ->outgoing() // Filtra apenas saídas
        ->dateRange($startDate, $endDate) // Filtra por intervalo de datas
        ->selectRaw('medicine_id, sum(quantity) as total')
        ->groupBy('medicine_id')
        ->orderByDesc('total')
        ->take(10)
        ->get();

    // Consumo por categoria (também corrigido)
    $categories = Medicine::join('movements', 'medicines.id', '=', 'movements.medicine_id')
        ->selectRaw('medicines.category, sum(movements.quantity) as total')
        ->where('movements.type', 'saida')
        ->whereBetween('movements.movement_date', [$startDate, $endDate]) // Alternativa sem escopo
        ->groupBy('medicines.category')
        ->orderByDesc('total')
        ->get();

    // Consumo mensal (últimos 12 meses)
    $monthlyConsumption = Movement::outgoing()
        ->selectRaw("DATE_FORMAT(movement_date, '%Y-%m') as month, sum(quantity) as total")
        ->where('movement_date', '>=', now()->subYear())
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    if ($request->has('pdf')) {
        $pdf = Pdf::loadView('reports.pdf.consumption', [
            'topConsumed' => $topConsumed,
            'categories' => $categories,
            'monthlyConsumption' => $monthlyConsumption,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $period
        ]);
        return $pdf->download('relatorio_consumo_'.now()->format('Y-m-d').'.pdf');
    }

    return view('reports.consumption', compact(
        'topConsumed', 'categories', 'monthlyConsumption', 'startDate', 'endDate', 'period'
    ));
}
    public function requestsReport(Request $request)
    {
        $status = $request->input('status', 'all');
        $urgency = $request->input('urgency', 'all');

        $requests = MedicineRequest::with(['medicine', 'user', 'responder'])
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($urgency !== 'all', function($query) use ($urgency) {
                return $query->where('urgency_level', $urgency);
            })
            ->latest()
            ->paginate(15);

        if ($request->has('pdf')) {
            $pdf = Pdf::loadView('reports.pdf.requests', compact('requests', 'status', 'urgency'));
            return $pdf->download('relatorio_solicitacoes_'.now()->format('Y-m-d').'.pdf');
        }

        return view('reports.requests', compact('requests', 'status', 'urgency'));
    }
}
