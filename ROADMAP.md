# 🚀 ERP System - Complete Development Roadmap

## نظام ERP لإدارة شركات التوريدات والخدمات (B2B ERP Platform)

---

## 📋 فهرس المحتويات

1. [نظرة عامة على المشروع](#نظرة-عامة)
2. [المرحلة الأولى: Database Design & Models](#المرحلة-الأولى-database-design--models)
3. [المرحلة الثانية: Controllers & Business Logic](#المرحلة-الثانية-controllers--business-logic)
4. [المرحلة الثالثة: APIs & Resources](#المرحلة-الثالثة-apis--resources)
5. [المرحلة الرابعة: Authentication & Authorization](#المرحلة-الرابعة-authentication--authorization)
6. [المرحلة الخامسة: Advanced Features](#المرحلة-الخامسة-advanced-features)
7. [المرحلة السادسة: Testing & Deployment](#المرحلة-السادسة-testing--deployment)

---

## 🎯 نظرة عامة

### الهدف من المشروع
نظام ERP متكامل لإدارة شركات التوريدات والخدمات يشمل:
- إدارة العملاء (CRM)
- إدارة المشاريع
- المبيعات والعقود
- المخزون والمشتريات
- النظام المحاسبي
- إدارة الموارد البشرية
- التقارير والتحليلات

### التقنيات المستخدمة
- **Backend**: Laravel 12 + PHP 8.4
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Testing**: PHPUnit
- **Code Style**: Laravel Pint

---

## 📊 المرحلة الأولى: Database Design & Models

### Phase 1.1: Core System Tables

#### 1. Companies & Structure Tables

```sql
-- companies table
- id (bigint, PK)
- name (string)
- email (string, unique)
- phone (string)
- address (text)
- logo (string, nullable)
- tax_number (string, nullable)
- commercial_register (string, nullable)
- status (enum: active, inactive)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Company -mfs
```

**Relationships:**
- hasMany: Branches, Departments, Users, Clients, Projects, etc.

---

```sql
-- branches table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- code (string, unique)
- address (text)
- phone (string)
- manager_id (FK → users, nullable)
- status (enum: active, inactive)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Branch -mfs
```

**Relationships:**
- belongsTo: Company, Manager (User)
- hasMany: Departments, Users

---

```sql
-- departments table
- id (bigint, PK)
- company_id (FK → companies)
- branch_id (FK → branches, nullable)
- name (string)
- code (string)
- manager_id (FK → users, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Department -mfs
```

**Relationships:**
- belongsTo: Company, Branch, Manager (User)
- hasMany: Users

---

#### 2. Users & Authentication Tables

```sql
-- users table
- id (bigint, PK)
- company_id (FK → companies)
- branch_id (FK → branches, nullable)
- department_id (FK → departments, nullable)
- name (string)
- email (string, unique)
- phone (string, nullable)
- password (string)
- role (string) -- e.g., 'admin', 'manager', 'employee'
- avatar (string, nullable)
- status (enum: active, inactive, suspended)
- email_verified_at (timestamp, nullable)
- remember_token (string, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model User -mfs
```

**Relationships:**
- belongsTo: Company, Branch, Department
- hasMany: Notifications, etc.

---

### Phase 1.2: CRM Module Tables

```sql
-- customers table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- phone (string)
- email (string)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Customer -mfs
```

**Relationships:**
- belongsTo: Company
- hasMany: Leads, SalesOrders, CustomerNotes

---

```sql
-- customer_notes table
- id (bigint, PK)
- customer_id (FK → customers)
- user_id (FK → users)
- note (text)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model CustomerNote -mfs
```

---

```sql
-- leads table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- source (string)
- status (string)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Lead -mfs
```

---

```sql
-- opportunities table
- id (bigint, PK)
- company_id (FK → companies)
- client_id (FK → clients)
- lead_id (FK → leads, nullable)
- title (string)
- description (text, nullable)
- value (decimal 15,2)
- probability (integer) -- 0-100%
- expected_close_date (date, nullable)
- status_id (FK → opportunity_statuses)
- assigned_to (FK → users, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Opportunity -mfs
```

---

```sql
-- opportunity_statuses table
- id (bigint, PK)
- name (string)
- color (string)
- order (integer)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model OpportunityStatus -mfs
```

---

```sql
-- follow_ups table
- id (bigint, PK)
- company_id (FK → companies)
- followable_type (string) -- polymorphic
- followable_id (bigint) -- polymorphic
- user_id (FK → users)
- type (enum: call, email, meeting, note)
- subject (string)
- description (text, nullable)
- follow_up_date (datetime)
- completed (boolean, default: false)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model FollowUp -mfs
```

**Polymorphic Relations:** Client, Lead, Opportunity

---

### Phase 1.3: Sales Module (Merged into SalesOrders)
<!-- Sales logic moved to Phase 1.2 in ERD structure -->

---

### Phase 1.4: Projects Module (Removed per ERD)
<!-- Project management removed as per simplified ERD -->

---

### Phase 1.5: Inventory & Purchases Module

```sql
-- categories table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- description (text, nullable)
- parent_id (FK → categories, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Category -mfs
```

---

```sql
-- subcategories table
- id (bigint, PK)
- category_id (FK → categories)
- name (string)
- description (text, nullable)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model Subcategory -mfs
```

---

```sql
-- products table
- id (bigint, PK)
- company_id (FK → companies)
- category_id (FK → categories, nullable)
- name (string)
- sku (string, unique)
- description (text, nullable)
- type (enum: product, service)
- unit (string) -- piece, kg, meter, hour, etc.
- purchase_price (decimal 15,2)
- selling_price (decimal 15,2)
- tax_rate (decimal 5,2, default: 0)
- stock_quantity (decimal 10,2, default: 0)
- min_stock_level (decimal 10,2, nullable)
- status (enum: active, inactive)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
```sql
-- categories table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Category -mfs
```

---

```sql
-- subcategories table
- id (bigint, PK)
- category_id (FK → categories)
- name (string)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model Subcategory -mfs
```

---

```sql
-- products table
- id (bigint, PK)
- company_id (FK → companies)
- subcategory_id (FK → subcategories)
- name (string)
- sku (string, unique)
- cost_price (decimal 15,2)
- selling_price (decimal 15,2)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Product -mfs
```

---

```sql
-- stocks table (Current Stock Levels per Branch)
- id (bigint, PK)
- product_id (FK → products)
- branch_id (FK → branches)
- quantity (integer)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model Stock -mfs
```

---

```sql
-- inventory_transactions table
- id (bigint, PK)
- product_id (FK → products)
- branch_id (FK → branches)
- type (string) -- e.g., 'in', 'out'
- quantity (integer)
- reference_type (string) -- e.g., 'SalesOrder', 'PurchaseOrder'
- reference_id (bigint)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model InventoryTransaction -mfs
```

---

```sql
-- suppliers table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- phone (string)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Supplier -mfs
```

---

```sql
-- purchase_orders table
- id (bigint, PK)
- supplier_id (FK → suppliers)
- company_id (FK → companies)
- status (string)
- total_amount (decimal 15,2)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model PurchaseOrder -mfs
```

---

```sql
-- purchase_order_items table
- id (bigint, PK)
- purchase_order_id (FK → purchase_orders)
- product_id (FK → products)
- quantity (integer)
- unit_price (decimal 15,2)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model PurchaseOrderItem -mfs
```

---

```sql
-- sales_orders table
- id (bigint, PK)
- company_id (FK → companies)
- customer_id (FK → customers)
- branch_id (FK → branches)
- status (string)
- subtotal (decimal 15,2)
- tax (decimal 15,2)
- discount (decimal 15,2)
- total (decimal 15,2)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model SalesOrder -mfs
```

---

```sql
-- sales_order_items table
- id (bigint, PK)
- sales_order_id (FK → sales_orders)
- product_id (FK → products)
- quantity (integer)
- unit_price (decimal 15,2)
- total_price (decimal 15,2)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model SalesOrderItem -mfs
```

---

### Phase 1.6: Accounting Module

```sql
-- invoices table
- id (bigint, PK)
- sales_order_id (FK → sales_orders)
- invoice_date (date)
- total_amount (decimal 15,2)
- status (string)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Invoice -mfs
```

---

```sql
-- payment_methods table
- id (bigint, PK)
- name (string)
```

**Model Command:**
```bash
php artisan make:model PaymentMethod -mfs
```

---

```sql
-- payments table
- id (bigint, PK)
- invoice_id (FK → invoices)
- payment_method_id (FK → payment_methods)
- amount (decimal 15,2)
- payment_date (date)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Payment -mfs
```

---

### Phase 1.6: Accounting Module

```sql
-- wallets table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- balance (decimal 15,2, default: 0)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model Wallet -mfs
```

---

```sql
-- transactions table (Accounting Transactions)
- id (bigint, PK)
- wallet_id (FK → wallets)
- type (string) -- e.g., 'income', 'expense'
- amount (decimal 15,2)
- reference_type (string)
- reference_id (bigint)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model Transaction -mfs
```

---

### Phase 1.7: HR Module

```sql
-- employees table
- id (bigint, PK)
- user_id (FK → users, unique)
- branch_id (FK → branches)
- job_title (string)
- salary (decimal 15,2)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Employee -mfs
```

---

```sql
-- payrolls table
- id (bigint, PK)
- employee_id (FK → employees)
- month (string)
- net_salary (decimal 15,2)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Payroll -mfs
```

---

### Phase 1.8: Notifications Module

```sql
-- notifications table
- id (bigint, PK)
- user_id (FK → users)
- title (string)
- body (text)
- is_read (boolean, default: false)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model Notification -mfs
```

---

## 🎮 المرحلة الثانية: Controllers & Business Logic

### Phase 2.1: Authentication Controllers

#### AuthController
```bash
php artisan make:controller Api/V1/Auth/AuthController
```

**Methods:**
- `register()` - تسجيل مستخدم جديد
- `login()` - تسجيل الدخول
- `logout()` - تسجيل الخروج
- `refresh()` - تحديث التوكن
- `me()` - بيانات المستخدم الحالي

#### PasswordController
```bash
php artisan make:controller Api/V1/Auth/PasswordController
```

**Methods:**
- `forgotPassword()` - طلب إعادة تعيين كلمة المرور
- `resetPassword()` - إعادة تعيين كلمة المرور
- `changePassword()` - تغيير كلمة المرور

---

### Phase 2.2: Core System Controllers

#### CompanyController
```bash
php artisan make:controller Api/CompanyController --api --model=Company --requests
```

#### BranchController
```bash
php artisan make:controller Api/BranchController --api --model=Branch --requests
```

#### UserController
```bash
php artisan make:controller Api/UserController --api --model=User --requests
```

---

### Phase 2.3: CRM Controllers

#### CustomerController
```bash
php artisan make:controller Api/CustomerController --api --model=Customer --requests
```

#### LeadController
```bash
php artisan make:controller Api/LeadController --api --model=Lead --requests
```

#### CustomerNoteController
```bash
php artisan make:controller Api/CustomerNoteController --api --model=CustomerNote --requests
```

---

### Phase 2.4: Products & Inventory Controllers

#### CategoryController
```bash
php artisan make:controller Api/CategoryController --api --model=Category --requests
```

#### SubcategoryController
```bash
php artisan make:controller Api/SubcategoryController --api --model=Subcategory --requests
```

#### ProductController
```bash
php artisan make:controller Api/ProductController --api --model=Product --requests
```

#### StockController
```bash
php artisan make:controller Api/StockController --api --model=Stock --requests
```

#### InventoryTransactionController
```bash
php artisan make:controller Api/InventoryTransactionController --api --model=InventoryTransaction --requests
```

---

### Phase 2.5: Sales & Purchases Controllers

#### SalesOrderController
```bash
php artisan make:controller Api/SalesOrderController --api --model=SalesOrder --requests
```

#### SalesOrderItemController
```bash
php artisan make:controller Api/SalesOrderItemController --api --model=SalesOrderItem --requests
```

#### SupplierController
```bash
php artisan make:controller Api/SupplierController --api --model=Supplier --requests
```

#### PurchaseOrderController
```bash
php artisan make:controller Api/PurchaseOrderController --api --model=PurchaseOrder --requests
```

#### PurchaseOrderItemController
```bash
php artisan make:controller Api/PurchaseOrderItemController --api --model=PurchaseOrderItem --requests
```

---

### Phase 2.6: Accounting & Payments Controllers

#### WalletController
```bash
php artisan make:controller Api/WalletController --api --model=Wallet --requests
```

#### TransactionController
```bash
php artisan make:controller Api/TransactionController --api --model=Transaction --requests
```

#### InvoiceController
```bash
php artisan make:controller Api/InvoiceController --api --model=Invoice --requests
```

#### PaymentMethodController
```bash
php artisan make:controller Api/PaymentMethodController --api --model=PaymentMethod --requests
```

#### PaymentController
```bash
php artisan make:controller Api/PaymentController --api --model=Payment --requests
```

---

### Phase 2.7: HR & Notifications Controllers

#### EmployeeController
```bash
php artisan make:controller Api/EmployeeController --api --model=Employee --requests
```

#### PayrollController
```bash
php artisan make:controller Api/PayrollController --api --model=Payroll --requests
```

#### NotificationController
```bash
php artisan make:controller Api/NotificationController --api --model=Notification --requests
```

---

### Phase 2.9: Reports & Dashboard Controllers

#### DashboardController
```bash
php artisan make:controller Api/V1/DashboardController
```

**Methods:**
- `index()` - لوحة التحكم الرئيسية
- `getKPIs()` - مؤشرات الأداء
- `getSalesChart()` - رسم بياني للمبيعات
- `getRevenueChart()` - رسم بياني للإيرادات
- `getProjectsStatus()` - حالة المشاريع

#### ReportController
```bash
php artisan make:controller Api/V1/ReportController
```

**Methods:**
- `salesReport()` - تقرير المبيعات
- `financialReport()` - تقرير مالي
- `projectsReport()` - تقرير المشاريع
- `employeePerformance()` - أداء الموظفين
- `inventoryReport()` - تقرير المخزون

---

## 📡 المرحلة الثالثة: APIs & Resources

### Phase 3.1: API Resources

#### Create Resources for All Models

```bash
# Core Resources
php artisan make:resource Api/CompanyResource
php artisan make:resource Api/BranchResource
php artisan make:resource Api/UserResource


# CRM Resources
php artisan make:resource Api/CustomerResource
php artisan make:resource Api/LeadResource
php artisan make:resource Api/CustomerNoteResource

# Inventory Resources
php artisan make:resource Api/ProductResource
php artisan make:resource Api/CategoryResource
php artisan make:resource Api/SubcategoryResource
php artisan make:resource Api/StockResource
php artisan make:resource Api/InventoryTransactionResource
php artisan make:resource Api/SupplierResource

# Sales & Purchases Resources
php artisan make:resource Api/SalesOrderResource
php artisan make:resource Api/SalesOrderItemResource
php artisan make:resource Api/PurchaseOrderResource
php artisan make:resource Api/PurchaseOrderItemResource

# Accounting Resources
php artisan make:resource Api/WalletResource
php artisan make:resource Api/TransactionResource
php artisan make:resource Api/InvoiceResource
php artisan make:resource Api/PaymentMethodResource
php artisan make:resource Api/PaymentResource

# HR Resources
php artisan make:resource Api/EmployeeResource
php artisan make:resource Api/PayrollResource
php artisan make:resource Api/NotificationResource
```

---

### Phase 3.2: API Routes Structure

**File:** `routes/api.php`

```php
Route::middleware(['auth:sanctum'])->group(function () {
    // Core
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('users', UserController::class);

    // CRM
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('leads', LeadController::class);
    Route::apiResource('customer-notes', CustomerNoteController::class);

    // Inventory
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('subcategories', SubcategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('stocks', StockController::class);
    Route::apiResource('inventory-transactions', InventoryTransactionController::class);
    Route::apiResource('suppliers', SupplierController::class);

    // Sales & Purchases
    Route::apiResource('sales-orders', SalesOrderController::class);
    Route::apiResource('sales-order-items', SalesOrderItemController::class);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::apiResource('purchase-order-items', PurchaseOrderItemController::class);

    // Accounting
    Route::apiResource('wallets', WalletController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('payment-methods', PaymentMethodController::class);
    Route::apiResource('payments', PaymentController::class);

    // HR
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('payrolls', PayrollController::class);
    Route::apiResource('notifications', NotificationController::class);
});
```

---

### Phase 3.3: API Routes Structure

**File:** `routes/api.php`

```php
// API Version 1
Route::prefix('v1')->group(function () {
    
    // Public Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [PasswordController::class, 'forgotPassword']);
    Route::post('/reset-password', [PasswordController::class, 'resetPassword']);
    
    // Protected Routes
    Route::middleware(['auth:sanctum'])->group(function () {
        
        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/change-password', [PasswordController::class, 'changePassword']);
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/dashboard/kpis', [DashboardController::class, 'getKPIs']);
        
        // Companies
        Route::apiResource('companies', CompanyController::class);
        Route::apiResource('branches', BranchController::class);
        Route::apiResource('departments', DepartmentController::class);
        
        // Users
        Route::apiResource('users', UserController::class);
        
        // CRM Module
        Route::prefix('crm')->group(function () {
            Route::apiResource('clients', ClientController::class);
            Route::apiResource('leads', LeadController::class);
            Route::apiResource('opportunities', OpportunityController::class);
            Route::apiResource('follow-ups', FollowUpController::class);
        });
        
        // Sales Module
        Route::prefix('sales')->group(function () {
            Route::apiResource('quotations', QuotationController::class);
            Route::apiResource('contracts', ContractController::class);
        });
        
        // Projects Module
        Route::prefix('projects')->group(function () {
            Route::apiResource('projects', ProjectController::class);
            Route::apiResource('tasks', TaskController::class);
        });
        
        // Inventory Module
        Route::prefix('inventory')->group(function () {
            Route::apiResource('categories', CategoryController::class);
            Route::apiResource('subcategories', SubcategoryController::class);
            Route::apiResource('products', ProductController::class);
            Route::apiResource('stocks', StockController::class);
            Route::apiResource('suppliers', SupplierController::class);
            Route::apiResource('purchase-orders', PurchaseOrderController::class);
        });
        
        // Accounting Module
        Route::prefix('accounting')->group(function () {
            Route::apiResource('wallets', WalletController::class);
            Route::apiResource('transactions', TransactionController::class);
            Route::apiResource('invoices', InvoiceController::class);
            Route::apiResource('payments', PaymentController::class);
            Route::apiResource('payment-methods', PaymentMethodController::class);
            Route::apiResource('expenses', ExpenseController::class);
        });
        
        // HR Module
        Route::prefix('hr')->group(function () {
            Route::apiResource('employees', EmployeeController::class);
            Route::apiResource('attendances', AttendanceController::class);
            Route::apiResource('leaves', LeaveController::class);
            Route::apiResource('payrolls', PayrollController::class);
        });
        
        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/sales', [ReportController::class, 'salesReport']);
            Route::get('/financial', [ReportController::class, 'financialReport']);
            Route::get('/projects', [ReportController::class, 'projectsReport']);
        });
    });
});
```

---

## 🔐 المرحلة الرابعة: Authentication & Authorization

### Phase 4.1: Sanctum Setup

```bash
# Install Sanctum (already included in Laravel 12)
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

**Configure in `config/sanctum.php`**

---

### Phase 4.2: Middleware

#### Create Custom Middleware

```bash
# Multi-tenant middleware
php artisan make:middleware EnsureUserBelongsToCompany


# Permission middleware
php artisan make:middleware CheckPermission
```

**Register in `bootstrap/app.php`:**

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'company' => \App\Http\Middleware\EnsureUserBelongsToCompany::class,
        'permission' => \App\Http\Middleware\CheckPermission::class,
    ]);
})
```

---

### Phase 4.3: Policies

```bash
# Create policies for major models
php artisan make:policy CompanyPolicy --model=Company
php artisan make:policy CustomerPolicy --model=Customer
php artisan make:policy ProductPolicy --model=Product
php artisan make:policy InvoicePolicy --model=Invoice
```

---

### Phase 4.4: Seeders

```bash
# Create seeders
php artisan make:seeder CompanySeeder
php artisan make:seeder UserSeeder
```

**Default Roles:**
- Super Admin
- Company Admin
- Manager
- Accountant
- Employee

---

## 🚀 المرحلة الخامسة: Advanced Features

### Phase 5.1: Notifications System
- New Customer notification
- Low Stock notification
- Invoice Paid notification
- Payroll processed notification

---

### Phase 5.2: Observers
```bash
# Audit logging
php artisan make:observer CustomerObserver --model=Customer
php artisan make:observer ProductObserver --model=Product
php artisan make:observer InvoiceObserver --model=Invoice
```

---

### Phase 5.3: Services Layer
- `CustomerService.php`
- `InventoryService.php`
- `AccountingService.php`
- `PayrollService.php`

---

## ✅ المرحلة السادسة: Testing & Deployment

- Auth Tests
- CRM Tests
- Inventory Tests
- Accounting Tests
- HR Tests

---

## 📊 Development Timeline Estimate

| Phase | Duration | Tasks |
|-------|----------|-------|
| **Phase 1: Database & Models** | 2 weeks | 20+ tables, migrations, models |
| **Phase 2: Controllers** | 3 weeks | API Controllers with logic |
| **Phase 3: APIs & Resources** | 1 week | Resources & Routes |
| **Phase 4: Auth & Authorization** | 1 week | Sanctum & Policies |
| **Phase 5: Advanced Features** | 2 weeks | Notifications & Services |
| **Phase 6: Testing & Deployment** | 1 week | Tests & Documentation |
| **Total** | **10 weeks** | Full ERP system |

---

## 🎯 Priority Order (MVP First Approach)

### Sprint 1: Core Foundation
- Companies, Branches, Users

### Sprint 2: CRM & Inventory
- Customers, Categories, Products, Stocks

### Sprint 3: Sales & Accounting
- SalesOrders, Invoices, Payments, Wallets

### Sprint 4: HR & Notifications
- Employees, Payrolls, Notifications

---

**Good Luck! 🚀**
