<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ClientInvoiceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaterialAndEquipmentController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TechnicianInvoiceController;
use App\Http\Controllers\WorkOrderController;
use App\Models\ClientInvoice;
use App\Models\TechnicianInvoice;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
//admin login
Route::get('/categories/{categoryId}/services', [ServiceCategoryController::class, 'getServices']);

Route::get('admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'submitLogin'])->name('admin.login.post');
Route::get('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('clients',ClientController::class);
    Route::post('/client/{client}/activate', [ClientController::class, 'activateAccount'])->name('client.activate');
    Route::post('/client/{client}/deactivate', [ClientController::class, 'deactivateAccount'])->name('client.deactivate');
    Route::resource('work-orders',WorkOrderController::class);
    Route::resource('technicians',TechnicianController::class);
    Route::post('/technician/{technician}/activate', [TechnicianController::class, 'activateAccount'])->name('technician.activate');
    Route::post('/technician/{technician}/deactivate', [TechnicianController::class, 'deactivateAccount'])->name('technician.deactivate');
    Route::resource('technician-invoices',TechnicianInvoice::class);
    Route::get('clients/{client}/work_order', [ClientController::class, 'workorders'])->name('clients.workorders');
    Route::get('work-order/{work_order}/materials', [WorkOrderController::class, 'materials'])->name('workorders.materials');
    Route::get('work-order/{work_order}/approve', [WorkOrderController::class, 'approveWorkOrder'])->name('workorder.approve');
});


