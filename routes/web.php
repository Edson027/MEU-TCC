<?php

use App\Http\Controllers\FornecedorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\MovimentarEstoque;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RestockOrderController;
use App\Http\Controllers\StockAlertController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use App\Models\Medicine;
use App\Http\Controllers\SupplyOrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
 
Route::resource('medicines', MedicineController::class);

    // Rotas para movimentações
    Route::get('/medicines/{medicine}/movements/create/{type}',
        [MovementController::class, 'create'])
        ->name('movements.create');
Route::get('/Produto/criar',[MovementController::class,'create'])->name('create.produto');
    Route::post('/medicines/{medicine}/movements',
        [MovementController::class, 'store'])
        ->name('movements.store');
// routes/web.php

//Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Rotas para medicamentos

});

Auth::routes();

Route::get('/PainelAdministrativo',[MedicineController::class,'administrador'])->name('PainelAdministrativo.dashboard');
Route::get('/home2', [App\Http\Controllers\HomeController::class, 'index'])->name('home2');
Route::get('/medicines/{medicine}/history', [MedicineController::class, 'history'])
    ->name('medicines.history');

Route::post('/medicines/{medicine}/restock', [MedicineController::class, 'restock'])
    ->name('medicines.restock');



// Rotas para movimentações
Route::get('/medicines/{medicine}/movements/create/{type}', [MovementController::class, 'create'])
    ->name('movements.create')
    ->where('type', 'entrada|saida');

Route::post('/medicines/{medicine}/movements', [MovementController::class, 'store'])
    ->name('movements.store');

Route::delete('/movements/{movement}', [MovementController::class, 'destroy'])
    ->name('movements.destroy')
    ->middleware('can:delete,movement');


/*
// Rotas para solicitações
Route::resource('requests', RequestController::class)->only(['index', 'create', 'store', 'show']);
Route::post('/requests/{request}/approve', [RequestController::class, 'approve'])->name('requests.approve');
Route::post('/requests/{request}/reject', [RequestController::class, 'reject'])->name('requests.reject');
*/
// Rotas para relatórios
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/consumption', [ReportController::class, 'consumptionReport'])->name('reports.consumption');
Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
Route::get('/reports/requests', [ReportController::class, 'requestsReport'])->name('reports.requests');




// Rotas para solicitações (requests) - autenticação necessária
//Route::middleware(['auth'])->group(function () {
    // Listagem de solicitações
    Route::get('/requests', [RequestController::class, 'index'])
        ->name('requests.index');


 Route::get('/requests/status', [RequestController::class, 'index2'])
        ->name('requests.pedido');


    // Formulário de nova solicitação
    Route::get('/requests/create', [RequestController::class, 'create'])
        ->name('requests.create');
        //->middleware('can:create,App\Models\Request');

    // Salvar nova solicitação
    Route::post('/requests', [RequestController::class, 'store'])
        ->name('requests.store');

    // Visualizar detalhes de uma solicitação
    Route::get('/requests/{request}', [RequestController::class, 'show'])
        ->name('requests.show');

    // Aprovar solicitação
    Route::post('/requests/{request}/approve', [RequestController::class, 'approve'])
        ->name('requests.approve');
       // ->middleware('can:approve,request');

    // Rejeitar solicitação
    Route::post('/requests/{request}/reject', [RequestController::class, 'reject'])
        ->name('requests.reject');
//        ->middleware('can:approve,request');


Route::put('requests/{request}', [RequestController::class, 'update'])->name('requests.update');

    //});
Route::get('/notifications', [MedicineController::class, 'notifications'])->name('notifications.index');
Route::put('/notifications/{notification}/read', [MedicineController::class, 'markAsRead'])->name('notifications.read');
Route::delete('/notifications/{notification}', [MedicineController::class, 'deleteNotification'])->name('notifications.destroy');

Route::post('/medicines/check', [MedicineController::class, 'checkStockAndValidity'])
    ->name('medicines.check')
    ->middleware('auth', 'admin');


    // Notificações
Route::prefix('notifications')->group(function () {
    Route::get('/', [MedicineController::class, 'notifications'])->name('notifications');
    Route::patch('/{notification}/mark-as-read', [MedicineController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/mark-all-as-read', [MedicineController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/{notification}', [MedicineController::class, 'deleteNotification'])->name('notifications.delete');
    Route::delete('/', [MedicineController::class, 'clearNotifications'])->name('notifications.clear');
});


//Route::resource('users', UsuarioController::class);
Route::get('/Usuarios',[UsuarioController::class,'create'])->name('users.create');

Route::get('/medicines/out-of-stock', [MedicineController::class, 'outOfStock'])->name('medicines.outOfStock');



 
  //  Route::middleware(['auth', 'can:view_stock_alerts'])->group(function() {
    // Dashboard de alertas
    Route::get('/stock-alerts', [StockAlertController::class, 'index'])->name('stock-alerts.index');
    
    // Solicitação de reposição
    Route::post('/stock-alerts/request-restock', [StockAlertController::class, 'requestRestock'])
        ->name('stock-alerts.request-restock');


        Route::get('/exportar',[StockAlertController::class,'exportar'])->name('stock.export');
    
    // Enviar notificações manualmente
    Route::post('/stock-alerts/send-notifications', [StockAlertController::class, 'sendLowStockNotifications'])
        ->name('stock-alerts.send-notifications');
    
    // Marcar notificação como lida
    Route::post('/notifications/{id}/read', [StockAlertController::class, 'markAsRead'])
        ->name('notifications.read');

//});

Route::get('/stock/check-low', function() {
    return Medicine::whereColumn('stock', '<', 'minimum_stock')
                  ->select('id', 'name', 'stock', 'minimum_stock')
                  ->get();
});

//Route::middleware(['auth', 'can:view_stock_alerts'])->group(function() {
    // Dashboard de alertas
    Route::get('/stock-alerts', [StockAlertController::class, 'index'])->name('stock-alerts.index');
    
    // Solicitação de reposição
    Route::post('/stock-alerts/request-restock', [StockAlertController::class, 'requestRestock'])
        ->name('stock.request-restock');
    
    // Enviar notificações manualmente
    Route::post('/stock-alerts/send-notifications', [StockAlertController::class, 'sendLowStockNotifications'])
        ->name('stock-alerts.send-notifications');
    
    // Marcar notificação como lida
    Route::post('/notifications/{id}/read', [StockAlertController::class, 'markAsRead'])
        ->name('notifications.read');

         // Rotas de aprovação
    Route::get('/stock-alerts/approval-list', [StockAlertController::class, 'approvalList'])
        ->name('stock-alerts.approval-list')
        ->middleware('can:approve_stock_alerts');
        
    Route::post('/stock-alerts/{stockAlert}/approve', [StockAlertController::class, 'approve'])
        ->name('stock-alerts.approve')
        ->middleware('can:approve_stock_alerts');
         
    Route::post('/stock-alerts/{stockAlert}/reject', [StockAlertController::class, 'reject'])
        ->name('stock-alerts.reject')
        ->middleware('can:approve_stock_alerts');
        
    Route::post('/stock-alerts/{stockAlert}/return-to-pending', [StockAlertController::class, 'returnToPending'])
        ->name('stock-alerts.return-to-pending');
//});  
  Route::get('/restock-orders/index', [RestockOrderController::class, 'index'])->name('restock-orders.index');
  Route::get('/restock-orders/create', [RestockOrderController::class, 'create'])->name('restock-orders.create');
  Route::get('/restock-orders', [RestockOrderController::class, 'index'])->name('restock-orders.index');
//Route::resource('restock-orders',[RestockOrderController::class]);
    Route::post('/restock-orders/store', [RestockOrderController::class, 'store'])->name('restock-orders.store');
    Route::post('/restock-orders/{order}/approve', [RestockOrderController::class, 'approve'])->name('restock-orders.approve');
    Route::post('/restock-orders/{order}/reject', [RestockOrderController::class, 'reject'])->name('restock-orders.reject');

    // Rotas para usuários
Route::resource('users', UserController::class);



// Rotas para Fornecedores
//Route::prefix('fornecedores')->name('fornecedors.')->group(function () {
    
    // Listagem de fornecedores
    Route::get('/fornecedores/index', [FornecedorController::class, 'index'])
         ->name('index')
         ;
    
    // Formulário de criação
    Route::get('/fornecedores/create', [FornecedorController::class, 'create'])
         ->name('create')
         ;
    
    // Salvar novo fornecedor
    Route::post('/fornecedores/salvar', [FornecedorController::class, 'store'])
         ->name('store')
         ;
    
    // Visualizar fornecedor
    Route::get('/fornecedores/{fornecedor}', [FornecedorController::class, 'show'])
         ->name('show')
         ;
    
    // Formulário de edição
    Route::get('/fornecedores/{fornecedor}/edit', [FornecedorController::class, 'edit'])
         ->name('edit')
         ;
    
    // Atualizar fornecedor
    Route::put('/fornecedores/{fornecedor}', [FornecedorController::class, 'update'])
         ->name('update')
         ;
    
    // Excluir fornecedor
    Route::delete('/fornecedores/{fornecedor}', [FornecedorController::class, 'destroy'])
         ->name('destroy')
         ;
    
    // API para select2
    Route::get('/api/fornecedores', [FornecedorController::class, 'apiFornecedores'])
         ->name('api.fornecedores')
         ;
//});

// Rota alternativa para compatibilidade
Route::resource('fornecedors', FornecedorController::class)
     ->parameters(['fornecedors' => 'fornecedor'])
     ->middleware(['auth']);
 

     Route::resource('supply-orders', SupplyOrderController::class);
     //Route::get('/supply-orders/index',[ SupplyOrderController::class,'index'])->name('supply-orders.index');

Route::post('/supply-orders/{supplyOrder}/approve', [SupplyOrderController::class, 'approve'])
    ->name('supply-orders.approve');

Route::post('/supply-orders/{supplyOrder}/reject', [SupplyOrderController::class, 'reject'])
    ->name('supply-orders.reject');

Route::post('/supply-orders/{supplyOrder}/receive', [SupplyOrderController::class, 'receive'])
    ->name('supply-orders.receive');

Route::post('/supply-orders/generate-auto', [SupplyOrderController::class, 'generateAutoOrders'])
    ->name('supply-orders.generate-auto');


    Route::get('movements', [MovimentarEstoque::class, 'index'])->name('movements.index');
Route::get('movements/export', [MovimentarEstoque::class, 'export'])->name('movements.export');
Route::get('movements/{movement}', [MovimentarEstoque::class, 'show'])->name('movements.show');

// Rotas específicas para criar movimentações a partir de um medicamento
Route::get('medicines/{medicine}/movements/create/{type}', [MovimentarEstoque::class, 'create'])->name('movements.create');
Route::post('medicines/{medicine}/movements', [MovimentarEstoque::class, 'store'])->name('movements.store');