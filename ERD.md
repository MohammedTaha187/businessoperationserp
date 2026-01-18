# 📊 ERP System - Detailed Entity Relationship Diagram (ERD)

![Technical ERD Visualization](public/images/erd.png)

هذا الرسم يوضح الجداول مع الحقول (Fields) والعلاقات (Relationships) بشكل تفصيلي بنفس أسلوب الصورة التي أرفقتها.

```mermaid
erDiagram
    COMPANIES {
        bigint id PK
        string name
        string email
        string phone
        text address
        timestamp created_at
    }

    BRANCHES {
        bigint id PK
        bigint company_id FK
        string name
        string location
        text address
    }

    USERS {
        bigint id PK
        bigint company_id FK
        bigint branch_id FK
        string name
        string email
        string password
    }

    CATEGORIES {
        bigint id PK
        bigint company_id FK
        string name
    }

    SUPPLIERS {
        bigint id PK
        bigint branch_id FK
        bigint category_id FK
        string name
        string contact_info
    }

    PRODUCTS {
        bigint id PK
        bigint category_id FK
        bigint supplier_id FK
        string name
        decimal price
        integer stock_quantity
    }

    INVENTORY {
        bigint id PK
        bigint product_id FK
        bigint branch_id FK
        integer quantity
        string type "In/Out"
    }

    SALES_ORDERS {
        bigint id PK
        bigint branch_id FK
        bigint user_id FK
        date order_date
        decimal total_amount
        string status
    }

    SALES_ORDER_ITEMS {
        bigint id PK
        bigint sales_order_id FK
        bigint product_id FK
        integer quantity
        decimal unit_price
    }

    PAYMENTS {
        bigint id PK
        bigint sales_order_id FK
        decimal amount
        date payment_date
        string method
    }

    COMPANIES ||--o{ BRANCHES : "has"
    COMPANIES ||--o{ CATEGORIES : "defines"
    BRANCHES ||--o{ USERS : "employs"
    
    CATEGORIES ||--o{ PRODUCTS : "contains"
    SUPPLIERS ||--o{ PRODUCTS : "supplies"
    
    PRODUCTS ||--o{ INVENTORY : "tracked_in"
    BRANCHES ||--o{ INVENTORY : "holds"
    
    BRANCHES ||--o{ SALES_ORDERS : "records"
    USERS ||--o{ SALES_ORDERS : "processes"
    
    SALES_ORDERS ||--o{ SALES_ORDER_ITEMS : "contains"
    PRODUCTS ||--o{ SALES_ORDER_ITEMS : "added_to"
    
    SALES_ORDERS ||--o{ PAYMENTS : "billed_by"
```

## 🗝️ ملاحظات على التصميم (Technical Notes):

1.  **Multi-Tenancy**: تبدأ العلاقة من `COMPANIES` التي تملك الفروع والأصناف.
2.  **Inventory Ledger**: جدول `INVENTORY` يعمل كسجل لكافة الحركات (Stock Movements).
3.  **Sales Flow**: يتم ربط الفاتورة (`SALES_ORDERS`) بالفرع والمستخدم والمدفوعات.

> [!TIP]
> تم تحديث هذا المخطط ليعكس بنية النظام الحالية والمستهدفة كما هو موضح في الـ [ROADMAP](file:///home/muhammad/Downloads/Laravel%20API-First/ERPSystem/ROADMAP.md).

## 🚀 Implementation Commands | أوامر التنفيذ

يمكنك استخدام الأوامر التالية لإنشاء النماذج (Models) وقواعد البيانات مع الـ Controllers داخل مجلد الـ API:

### 1. الكيانات الأساسية (Foundational Entities)
```bash
# Company
php artisan make:model Company -mfs
php artisan make:controller Api/CompanyController --api --model=Company --requests

# Branch
php artisan make:model Branch -mfs
php artisan make:controller Api/BranchController --api --model=Branch --requests
```

### 2. التصنيفات والموردين (Catalog & Suppliers)
```bash
# Category
php artisan make:model Category -mfs
php artisan make:controller Api/CategoryController --api --model=Category --requests

# Supplier
php artisan make:model Supplier -mfs
php artisan make:controller Api/SupplierController --api --model=Supplier --requests
```

### 3. المنتجات والمخزن (Products & Inventory)
```bash
# Product
php artisan make:model Product -mfs
php artisan make:controller Api/ProductController --api --model=Product --requests

# Inventory
php artisan make:model Inventory -mfs
php artisan make:controller Api/InventoryController --api --model=Inventory --requests
```

### 4. المبيعات والمدفوعات (Sales & Payments)
```bash
# Sales Order
php artisan make:model SalesOrder -mfs
php artisan make:controller Api/SalesOrderController --api --model=SalesOrder --requests

# Sales Order Item
php artisan make:model SalesOrderItem -mfs
php artisan make:controller Api/SalesOrderItemController --api --model=SalesOrderItem --requests

# Payment
php artisan make:model Payment -mfs
php artisan make:controller Api/PaymentController --api --model=Payment --requests
```

> [!NOTE]
> - علم `-mfs` يقوم بإنشاء (Migration, Factory, Seeder).
> - الـ Controller يتم إنشاؤه داخل مجلد `Api` مع توفير الـ Form Requests (`Store` & `Update`).
