<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\WorkOrderController;
use App\Http\Controllers\MaterialAndEquipmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['prefix' => 'v1'] , function () {
    Route::get('/work-orders/nearby/{latitude}/{longitude}', [WorkOrderController::class, 'listNearbyWorkOrders']);
    Route::get('work-orders/today', [WorkOrderController::class, 'listWorkOrdersToday']);
    Route::get('/dashboard', [WorkOrderController::class, 'dashboard']);
    Route::get('/test-connection', [AuthController::class, 'testConnection']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
   Route::get('/work-orders/unassigned', [WorkOrderController::class, 'listUnassignedWorkOrders']);
    Route::get('/work-orders/assigned', [WorkOrderController::class, 'listAssignedWorkOrders']);
    Route::get('/work-orders/in-process/{technicianId}', [WorkOrderController::class, 'listInProcessWorkOrders']);
    Route::get('/work-orders/completed/{technicianId}', [WorkOrderController::class, 'listCompletedWorkOrders']);
    Route::post('/work-orders/{workOrderId}/images', [WorkOrderController::class, 'saveWorkOrderImage']);
    Route::post('/work-orders/{workOrderId}/technician-report', [WorkOrderController::class, 'saveWorkOrderTechnicianReport']);
    Route::post('/work-orders/{workOrderId}/accept', [WorkOrderController::class, 'acceptWorkOrder']);
    Route::post('/work-orders/{workOrderId}/reject', [WorkOrderController::class, 'rejectWorkOrder']);
    Route::get('/materials-equipments/', [\App\Http\Controllers\API\V1\MaterialAndEquipmentController::class, 'listMaterialsAndEquipment']);
    Route::post('/work-orders/{workOrderId}/materials', [WorkOrderController::class, 'saveWorkOrderMaterials']);
    Route::post('/work-orders/{workOrderId}/checkin', [WorkOrderController::class, 'checkInWorkOrder'] );
    Route::post('/work-orders/{workOrderId}/checkout', [WorkOrderController::class, 'checkOutWorkOrder'] );
    Route::post('/work-orders/{workOrderId}/success', [WorkOrderController::class, 'successWorkOrder'] );
    Route::post('/work-orders/{workOrderId}/revisit', [WorkOrderController::class, 'revisitWorkOrder'] );
    Route::post('/work-orders/{workOrderId}/complete', [WorkOrderController::class, 'completeWorkOrder']);
    Route::get('/dashboard',[WorkOrderController::class, 'dashboard']);

// Route::get('/sign-up/sms',[AuthController::class, 'sendSms']);
//    Route::get('/sign-up/verify', [AuthController::class, 'verifySms']);
//    Route::post('/sign-up/verify', [AuthController::class, 'verifySms']);
//    Route::post('/sign-up', [AuthController::class, 'register']);
//    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/profile',[AuthController::class,'profile']);
    Route::get('/work-orders/{workOrder}', [WorkOrderController::class, 'getWorkorderDetails']);
    Route::post('/work-orders/{workOrderId}/images', [WorkOrderController::class, 'saveWorkOrderImage']);
    Route::post('/work-orders/{workOrderId}/technician-report', [WorkOrderController::class, 'saveWorkOrderTechnicianReport']);
    Route::post('/work-orders/{workOrderId}/accept', [WorkOrderController::class, 'acceptWorkOrder']);
    Route::post('/work-orders/{workOrderId}/reject', [WorkOrderController::class, 'rejectWorkOrder']);
    Route::get('/materials-equipments/', [MaterialAndEquipmentController::class, 'listMaterialsAndEquipment']);
    Route::post('/work-orders/{workOrderId}/materials', [WorkOrderController::class, 'saveWorkOrderMaterials']);
    Route::post('/work-orders/{workOrderId}/checkin', [WorkOrderController::class, 'checkInWorkOrder']);
    Route::post('/work-orders/{workOrderId}/checkout', [WorkOrderController::class, 'checkOutWorkOrder']);
    Route::post('/work-orders/{workOrderId}/success', [WorkOrderController::class, 'successWorkOrder']);
    Route::post('/work-orders/{workOrderId}/revisit', [WorkOrderController::class, 'revisitWorkOrder']);
    Route::post('/work-orders/{workOrderId}/complete', [WorkOrderController::class, 'completeWorkOrder']);
    Route::middleware('auth:sanctum')->get('/dashboard', [WorkOrderController::class, 'dashboard']);


    Route::post('/register/verify-verification-code/', [AuthController::class, 'verifyVerificationCode']);
    Route::post('register/send-verification-code/', [AuthController::class, 'sendVerificationCode']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/sign-up/verify', [AuthController::class, 'verifySms']);
    Route::post('/sign-up/verify', [AuthController::class, 'verifySms']);
    Route::get('/sms/{phone_number}', [AuthController::class, 'SmsSignup']);

    //get today work
    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
    Route::middleware('auth:sanctum')->get('/work-orders/summary/', [WorkOrderController::class, 'userSummery']);
});
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


