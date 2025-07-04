<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MovementController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReportController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
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
/*
Route::get('/requests/{request}/{id}', [RequestController::class, 'edit'])
        ->name('requests.edit');
*/
/*
Route::get('requests/{request}/{id}/edit', [RequestController::class, 'edit'])->name('requests.edit');
*/

Route::get('requests/{request}/edit', [RequestController::class, 'edit'])->name('requests.edit');
/*
        Route::put('/requests/{request}', [RequestController::class, 'update'])
    ->name('requests.update');
*/
Route::put('requests/{request}', [RequestController::class, 'update'])->name('requests.update');

    //});
Route::get('/notifications', [MedicineController::class, 'notifications'])->name('notifications.index');
Route::put('/notifications/{notification}/read', [MedicineController::class, 'markAsRead'])->name('notifications.read');
Route::delete('/notifications/{notification}', [MedicineController::class, 'deleteNotification'])->name('notifications.destroy');