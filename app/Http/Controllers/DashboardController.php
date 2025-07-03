<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Movement;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $criticalStock = Medicine::whereColumn('stock', '<', 'minimum_stock')
            ->count();

        $expiringSoon = Medicine::where('expiration_date', '<=', now()->addMonths(1))
            ->count();

        $pendingRequests = Request::where('status', 'pending')
            ->count();

        $totalMedicines = Medicine::count();

        $latestRequests = Request::with(['medicine', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $stockByCategory = Medicine::select('category', DB::raw('sum(stock) as total'))
            ->groupBy('category')
            ->get();

        $monthlyConsumption = Movement::where('type', 'saida')
            ->selectRaw("DATE_FORMAT(movement_date, '%Y-%m') as month, sum(quantity) as total")
            ->where('movement_date', '>', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dashboard', compact(
            'criticalStock',
            'expiringSoon',
            'pendingRequests',
            'totalMedicines',
            'latestRequests',
            'stockByCategory',
            'monthlyConsumption'
        ));
    }
}
