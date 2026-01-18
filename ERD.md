# 📊 ERP System - Comprehensive Entity Relationship Diagram (ERD)

![Full System ERD Visualization](public/images/erd_new.png)

هذا الرسم يوضح الهيكلية المتكاملة للنظام (Full ERP Architecture) والتي تشمل أنظمة فرعية (CRM, HR, Accounting, etc.).

erDiagram

%% ================= CORE SYSTEM =================
COMPANIES ||--o{ BRANCHES : manages
COMPANIES ||--o{ USERS : owns
BRANCHES ||--o{ USERS : employs

USERS ||--o{ USER_ROLES : assigned
ROLES ||--o{ USER_ROLES : includes

COMPANIES ||--o{ ROLES : defines

%% ================= CRM =================
COMPANIES ||--o{ CUSTOMERS : serves
COMPANIES ||--o{ LEADS : tracks
CUSTOMERS ||--o{ CUSTOMER_NOTES : has

%% ================= PRODUCTS & INVENTORY =================
COMPANIES ||--o{ CATEGORIES : organizes
CATEGORIES ||--o{ SUBCATEGORIES : contains
SUBCATEGORIES ||--o{ PRODUCTS : classifies

PRODUCTS ||--o{ STOCKS : stored_in
BRANCHES ||--o{ STOCKS : holds

PRODUCTS ||--o{ INVENTORY_TRANSACTIONS : tracked_by
BRANCHES ||--o{ INVENTORY_TRANSACTIONS : records

%% ================= SUPPLIERS & PURCHASES =================
COMPANIES ||--o{ SUPPLIERS : deals_with
SUPPLIERS ||--o{ PURCHASE_ORDERS : receives
PURCHASE_ORDERS ||--o{ PURCHASE_ORDER_ITEMS : contains
PRODUCTS ||--o{ PURCHASE_ORDER_ITEMS : purchased

%% ================= SALES =================
COMPANIES ||--o{ SALES_ORDERS : records
CUSTOMERS ||--o{ SALES_ORDERS : places
SALES_ORDERS ||--o{ SALES_ORDER_ITEMS : includes
PRODUCTS ||--o{ SALES_ORDER_ITEMS : sold

%% ================= INVOICES & PAYMENTS =================
SALES_ORDERS ||--o{ INVOICES : generates
INVOICES ||--o{ PAYMENTS : paid_by
PAYMENT_METHODS ||--o{ PAYMENTS : used_in

%% ================= ACCOUNTING =================
COMPANIES ||--o{ WALLETS : owns
WALLETS ||--o{ TRANSACTIONS : logs

%% ================= HR SYSTEM =================
USERS ||--|| EMPLOYEES : represents
BRANCHES ||--o{ EMPLOYEES : manages
EMPLOYEES ||--o{ PAYROLLS : receives

%% ================= NOTIFICATIONS =================
USERS ||--o{ NOTIFICATIONS : receives


%% ================= TABLE DEFINITIONS =================

COMPANIES {
    bigint id PK
    string name
    string email
    string phone
    timestamp created_at
}

BRANCHES {
    bigint id PK
    bigint company_id FK
    string name
    string location
}

USERS {
    bigint id PK
    bigint company_id FK
    bigint branch_id FK
    string name
    string email
    string password
}

ROLES {
    bigint id PK
    bigint company_id FK
    string name
}

USER_ROLES {
    bigint user_id FK
    bigint role_id FK
}

CUSTOMERS {
    bigint id PK
    bigint company_id FK
    string name
    string phone
    string email
}

CUSTOMER_NOTES {
    bigint id PK
    bigint customer_id FK
    bigint user_id FK
    text note
}

LEADS {
    bigint id PK
    bigint company_id FK
    string name
    string source
    string status
}

CATEGORIES {
    bigint id PK
    bigint company_id FK
    string name
}

SUBCATEGORIES {
    bigint id PK
    bigint category_id FK
    string name
}

PRODUCTS {
    bigint id PK
    bigint company_id FK
    bigint subcategory_id FK
    string name
    string sku
    decimal cost_price
    decimal selling_price
}

STOCKS {
    bigint id PK
    bigint product_id FK
    bigint branch_id FK
    integer quantity
}

INVENTORY_TRANSACTIONS {
    bigint id PK
    bigint product_id FK
    bigint branch_id FK
    string type
    integer quantity
    string reference_type
    bigint reference_id
}

SUPPLIERS {
    bigint id PK
    bigint company_id FK
    string name
    string phone
}

PURCHASE_ORDERS {
    bigint id PK
    bigint supplier_id FK
    bigint company_id FK
    string status
    decimal total_amount
}

PURCHASE_ORDER_ITEMS {
    bigint id PK
    bigint purchase_order_id FK
    bigint product_id FK
    integer quantity
    decimal unit_price
}

SALES_ORDERS {
    bigint id PK
    bigint company_id FK
    bigint customer_id FK
    bigint branch_id FK
    string status
    decimal subtotal
    decimal tax
    decimal discount
    decimal total
}

SALES_ORDER_ITEMS {
    bigint id PK
    bigint sales_order_id FK
    bigint product_id FK
    integer quantity
    decimal unit_price
    decimal total_price
}

INVOICES {
    bigint id PK
    bigint sales_order_id FK
    date invoice_date
    decimal total_amount
    string status
}

PAYMENT_METHODS {
    bigint id PK
    string name
}

PAYMENTS {
    bigint id PK
    bigint invoice_id FK
    bigint payment_method_id FK
    decimal amount
    date payment_date
}

WALLETS {
    bigint id PK
    bigint company_id FK
    string name
    decimal balance
}

TRANSACTIONS {
    bigint id PK
    bigint wallet_id FK
    string type
    decimal amount
    string reference_type
    bigint reference_id
}

EMPLOYEES {
    bigint id PK
    bigint user_id FK
    bigint branch_id FK
    string job_title
    decimal salary
}

PAYROLLS {
    bigint id PK
    bigint employee_id FK
    string month
    decimal net_salary
}

NOTIFICATIONS {
    bigint id PK
    bigint user_id FK
    string title
    text body
    boolean is_read
}

```

---
php artisan make:model Company -mfs --api --requests
php artisan make:model Branch -mfs --api --requests
php artisan make:model User -mfs --api --requests
php artisan make:model Role -mfs --api --requests
php artisan make:model UserRole -mfs
 

php artisan make:model Customer -mfs --api --requests
php artisan make:model Lead -mfs --api --requests
php artisan make:model CustomerNote -mfs --api --requests



php artisan make:model Category -mfs --api --requests
php artisan make:model Subcategory -mfs --api --requests
php artisan make:model Product -mfs --api --requests
php artisan make:model Stock -mfs --api --requests
php artisan make:model InventoryTransaction -mfs --api --requests

php artisan make:model SalesOrder -mfs --api --requests
php artisan make:model SalesOrderItem -mfs --api --requests
php artisan make:model Supplier -mfs --api --requests
php artisan make:model PurchaseOrder -mfs --api --requests
php artisan make:model PurchaseOrderItem -mfs --api --requests


php artisan make:model Wallet -mfs --api --requests
php artisan make:model Transaction -mfs --api --requests
php artisan make:model Invoice -mfs --api --requests
php artisan make:model Payment -mfs --api --requests
php artisan make:model PaymentMethod -mfs --api --requests


php artisan make:model Employee -mfs --api --requests
php artisan make:model Payroll -mfs --api --requests
php artisan make:model Notification -mfs --api --requests
