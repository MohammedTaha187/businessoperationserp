<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\CustomerNoteController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\InventoryTransactionController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\LeadController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\PaymentMethodController;
use App\Http\Controllers\Api\V1\PayrollController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\PurchaseOrderController;
use App\Http\Controllers\Api\V1\PurchaseOrderItemController;
use App\Http\Controllers\Api\V1\SalesOrderController;
use App\Http\Controllers\Api\V1\SalesOrderItemController;
use App\Http\Controllers\Api\V1\StockController;
use App\Http\Controllers\Api\V1\SubcategoryController;
use App\Http\Controllers\Api\V1\SupplierController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\WalletController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('subcategories', SubcategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('stocks', StockController::class);
    Route::apiResource('inventory-transactions', InventoryTransactionController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('leads', LeadController::class);
    Route::apiResource('customer-notes', CustomerNoteController::class);
    Route::apiResource('sales-orders', SalesOrderController::class);
    Route::apiResource('sales-order-items', SalesOrderItemController::class);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::apiResource('purchase-order-items', PurchaseOrderItemController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('payment-methods', PaymentMethodController::class);
    Route::apiResource('wallets', WalletController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('payrolls', PayrollController::class);
    Route::apiResource('notifications', NotificationController::class);
});
