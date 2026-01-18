# 📊 ERP System - Comprehensive Entity Relationship Diagram (ERD)

![Full System ERD Visualization](public/images/erd_new.png)

هذا الرسم يوضح الهيكلية المتكاملة للنظام (Full ERP Architecture) والتي تشمل أنظمة فرعية (CRM, HR, Accounting, etc.).

```mermaid
erDiagram
    %% Core System
    COMPANIES ||--o{ BRANCHES : "manages"
    COMPANIES ||--o{ ROLES : "defines"
    BRANCHES ||--o{ USERS : "employs"
    USERS }|--|| ROLES : "assigned"

    %% CRM System
    COMPANIES ||--o{ CUSTOMERS : "serves"
    COMPANIES ||--o{ LEADS : "tracks"
    CUSTOMERS ||--o{ CUSTOMER_NOTES : "has"

    %% Products & Inventory
    COMPANIES ||--o{ CATEGORIES : "organizes"
    CATEGORIES ||--o{ SUBCATEGORIES : "refines"
    CATEGORIES ||--o{ PRODUCTS : "contains"
    PRODUCTS ||--o{ STOCKS : "stored_in"
    BRANCHES ||--o{ STOCKS : "holds"

    %% Suppliers & Purchases
    COMPANIES ||--o{ SUPPLIERS : "deals_with"
    SUPPLIERS ||--o{ PURCHASE_ORDERS : "receives"

    %% Sales & Financials
    COMPANIES ||--o{ SALES_ORDERS : "records"
    SALES_ORDERS ||--o{ INVOICES : "generates"
    INVOICES ||--o{ PAYMENTS : "paid_by"
    PAYMENT_METHODS ||--o{ PAYMENTS : "used_in"

    %% Accounting
    COMPANIES ||--o{ WALLETS : "owns"
    WALLETS ||--o{ TRANSACTIONS : "logs"

    %% HR System
    BRANCHES ||--o{ EMPLOYEES : "manages"
    EMPLOYEES ||--o{ PAYROLLS : "billed_in"

    %% Notifications
    USERS ||--o{ NOTIFICATIONS : "receives"

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
        text address
        string location
    }

    ROLES {
        bigint id PK
        bigint company_id FK
        string name
        json permissions
    }

    CUSTOMERS {
        bigint id PK
        bigint company_id FK
        string name
        text address
    }

    LEADS {
        bigint id PK
        bigint company_id FK
        string name
        string source
        string status
    }

    PRODUCTS {
        bigint id PK
        bigint company_id FK
        bigint category_id FK
        string name
        string sku
        decimal price
    }

    STOCKS {
        bigint id PK
        bigint product_id FK
        bigint branch_id FK
        integer quantity
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
        string type "Income/Expense"
        decimal amount
    }

    EMPLOYEES {
        bigint id PK
        bigint company_id FK
        bigint branch_id FK
        string name
        string job_title
        decimal base_salary
    }
```

---

## 🚀 Implementation Commands | أوامر التنفيذ (Laravel API-First)

استخدم الأوامر التالية لإنشاء النظام بالكامل برمجياً مع الـ Controllers داخل مجلد `Api`:

### 1️⃣ Core & Business Structure
```bash
# Company, Branch, Role
php artisan make:model Company -mfs --api --requests
php artisan make:model Branch -mfs --api --requests
php artisan make:model Role -mfs --api --requests
```

### 2️⃣ CRM (Customers & Leads)
```bash
# Customer, Lead, Note
php artisan make:model Customer -mfs --api --requests
php artisan make:model Lead -mfs --api --requests
php artisan make:model CustomerNote -mfs --api --requests
```

### 3️⃣ Products & Inventory
```bash
# Category, Subcategory, Product, Stock
php artisan make:model Category -mfs --api --requests
php artisan make:model Subcategory -mfs --api --requests
php artisan make:model Product -mfs --api --requests
php artisan make:model Stock -mfs --api --requests
```

### 4️⃣ Sales & Purchases
```bash
# SalesOrder, PurchaseOrder, Supplier
php artisan make:model SalesOrder -mfs --api --requests
php artisan make:model PurchaseOrder -mfs --api --requests
php artisan make:model Supplier -mfs --api --requests
```

### 5️⃣ Accounting & Payments
```bash
# Wallet, Transaction, Invoice, Payment, PaymentMethod
php artisan make:model Wallet -mfs --api --requests
php artisan make:model Transaction -mfs --api --requests
php artisan make:model Invoice -mfs --api --requests
php artisan make:model Payment -mfs --api --requests
php artisan make:model PaymentMethod -mfs --api --requests
```

### 6️⃣ HR System & Notifications
```bash
# Employee, Payroll, Notification
php artisan make:model Employee -mfs --api --requests
php artisan make:model Payroll -mfs --api --requests
php artisan make:model Notification -mfs --api --requests
```

> [!IMPORTANT]
> - جميع الـ Controllers سيتم إنشاؤها تلقائياً داخل **`app/Http/Controllers/Api/`** عند تشغيل الأوامر بالترتيب.
> - الـ Migrations جاهزة لتعريف العلاقات كما في المخطط أعلاه.
