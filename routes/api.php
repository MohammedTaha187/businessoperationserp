<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\FinancialRecordController;
use App\Http\Controllers\Api\V1\InteractionNoteController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\InventoryTransactionController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\OpportunityController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\OrderItemController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\PaymentMethodController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SalaryController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // --- Public Auth Routes ---
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    // Social Auth Routes
    Route::get('auth/{provider}/redirect', [AuthController::class, 'redirectToProvider']);
    Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,1');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:3,1');
    Route::post('verify-email', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('resend-verification', [AuthController::class, 'resendVerification'])->name('verification.resend');

    // --- Protected Routes ---
    Route::middleware('auth:sanctum')->group(function () {

        // Profiles & Auth
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);

        // Company & Organization
        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('branches', BranchController::class);

        // Auth & Users
        Route::apiResource('users', UserController::class);

        // CRM
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('opportunities', OpportunityController::class);
        Route::apiResource('interaction-notes', InteractionNoteController::class);

        // Inventory & Products
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('inventories', InventoryController::class);
        Route::apiResource('inventory-transactions', InventoryTransactionController::class);

        // Orders & Invoices & Finances
        Route::apiResource('orders', OrderController::class);
        Route::apiResource('order-items', OrderItemController::class);
        Route::apiResource('invoices', InvoiceController::class);
        Route::apiResource('payment-methods', PaymentMethodController::class);
        Route::apiResource('wallets', WalletController::class);
        Route::apiResource('financial-records', FinancialRecordController::class);

        // HR
        Route::apiResource('employees', EmployeeController::class);
        Route::apiResource('salaries', SalaryController::class);

        // System
        Route::apiResource('notifications', NotificationController::class);
        // Payments
        Route::post('payment/checkout', [PaymentController::class, 'checkout']);
    });
});
