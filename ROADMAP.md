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
- belongsToMany: Roles (via role_user)
- hasMany: Tasks, Attendances, Leaves, etc.

---

```sql
-- roles table
- id (bigint, PK)
- name (string, unique)
- display_name (string)
- description (text, nullable)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model Role -mfs
```

**Relationships:**
- belongsToMany: Users, Permissions

---

```sql
-- permissions table
- id (bigint, PK)
- name (string, unique)
- display_name (string)
- module (string) -- e.g., 'clients', 'projects', 'invoices'
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model Permission -mfs
```

---

```sql
-- role_user (pivot)
- id (bigint, PK)
- user_id (FK → users)
- role_id (FK → roles)
- created_at, updated_at
```

---

```sql
-- permission_role (pivot)
- id (bigint, PK)
- permission_id (FK → permissions)
- role_id (FK → roles)
- created_at, updated_at
```

---

### Phase 1.2: CRM Module Tables

```sql
-- clients table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- email (string)
- phone (string)
- address (text, nullable)
- tax_number (string, nullable)
- client_type (enum: individual, company)
- status_id (FK → client_statuses)
- assigned_to (FK → users, nullable)
- notes (text, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Client -mfs
```

**Relationships:**
- belongsTo: Company, Status (ClientStatus), AssignedUser (User)
- hasMany: Leads, Opportunities, Contracts, Invoices

---

```sql
-- client_statuses table
- id (bigint, PK)
- name (string)
- color (string) -- hex color
- order (integer)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model ClientStatus -mfs
```

**Default Statuses:** New, Contacted, Qualified, Proposal, Negotiation, Won, Lost

---

```sql
-- leads table
- id (bigint, PK)
- company_id (FK → companies)
- client_id (FK → clients, nullable)
- title (string)
- source (enum: website, referral, cold_call, social_media, other)
- value (decimal 15,2, nullable)
- status_id (FK → lead_statuses)
- assigned_to (FK → users, nullable)
- notes (text, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Lead -mfs
```

---

```sql
-- lead_statuses table
- id (bigint, PK)
- name (string)
- color (string)
- order (integer)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model LeadStatus -mfs
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

### Phase 1.3: Sales & Contracts Module

```sql
-- quotations table
- id (bigint, PK)
- company_id (FK → companies)
- client_id (FK → clients)
- quotation_number (string, unique)
- subject (string)
- quotation_date (date)
- valid_until (date)
- subtotal (decimal 15,2)
- tax_amount (decimal 15,2)
- discount_amount (decimal 15,2)
- total (decimal 15,2)
- status (enum: draft, sent, accepted, rejected, expired)
- notes (text, nullable)
- terms_conditions (text, nullable)
- created_by (FK → users)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Quotation -mfs
```

---

```sql
-- quotation_items table
- id (bigint, PK)
- quotation_id (FK → quotations)
- product_id (FK → products, nullable)
- description (text)
- quantity (decimal 10,2)
- unit_price (decimal 15,2)
- tax_rate (decimal 5,2)
- discount_rate (decimal 5,2)
- total (decimal 15,2)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model QuotationItem -mfs
```

---

```sql
-- contracts table
- id (bigint, PK)
- company_id (FK → companies)
- client_id (FK → clients)
- quotation_id (FK → quotations, nullable)
- contract_number (string, unique)
- subject (string)
- contract_date (date)
- start_date (date)
- end_date (date, nullable)
- subtotal (decimal 15,2)
- tax_amount (decimal 15,2)
- discount_amount (decimal 15,2)
- total (decimal 15,2)
- status (enum: draft, active, completed, cancelled)
- notes (text, nullable)
- terms_conditions (text, nullable)
- created_by (FK → users)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Contract -mfs
```

---

```sql
-- contract_items table
- id (bigint, PK)
- contract_id (FK → contracts)
- product_id (FK → products, nullable)
- description (text)
- quantity (decimal 10,2)
- unit_price (decimal 15,2)
- tax_rate (decimal 5,2)
- discount_rate (decimal 5,2)
- total (decimal 15,2)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model ContractItem -mfs
```

---

### Phase 1.4: Projects Module

```sql
-- projects table
- id (bigint, PK)
- company_id (FK → companies)
- client_id (FK → clients)
- contract_id (FK → contracts, nullable)
- name (string)
- description (text, nullable)
- start_date (date)
- end_date (date, nullable)
- budget (decimal 15,2, nullable)
- status (enum: planning, in_progress, on_hold, completed, cancelled)
- progress (integer, default: 0) -- 0-100%
- priority (enum: low, medium, high, urgent)
- manager_id (FK → users, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Project -mfs
```

---

```sql
-- project_phases table
- id (bigint, PK)
- project_id (FK → projects)
- name (string)
- description (text, nullable)
- start_date (date)
- end_date (date, nullable)
- status (enum: pending, in_progress, completed)
- progress (integer, default: 0)
- order (integer)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model ProjectPhase -mfs
```

---

```sql
-- tasks table
- id (bigint, PK)
- company_id (FK → companies)
- project_id (FK → projects)
- phase_id (FK → project_phases, nullable)
- title (string)
- description (text, nullable)
- start_date (date, nullable)
- due_date (date, nullable)
- status (enum: todo, in_progress, review, completed, cancelled)
- priority (enum: low, medium, high, urgent)
- progress (integer, default: 0)
- estimated_hours (decimal 8,2, nullable)
- actual_hours (decimal 8,2, nullable)
- created_by (FK → users)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Task -mfs
```

---

```sql
-- task_assignments table
- id (bigint, PK)
- task_id (FK → tasks)
- user_id (FK → users)
- assigned_at (timestamp)
- assigned_by (FK → users)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model TaskAssignment -mfs
```

---

```sql
-- task_comments table
- id (bigint, PK)
- task_id (FK → tasks)
- user_id (FK → users)
- comment (text)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model TaskComment -mfs
```

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
php artisan make:model Product -mfs
```

---

```sql
-- suppliers table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- email (string, nullable)
- phone (string)
- address (text, nullable)
- tax_number (string, nullable)
- status (enum: active, inactive)
- notes (text, nullable)
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
- company_id (FK → companies)
- supplier_id (FK → suppliers)
- order_number (string, unique)
- order_date (date)
- expected_delivery_date (date, nullable)
- subtotal (decimal 15,2)
- tax_amount (decimal 15,2)
- total (decimal 15,2)
- status (enum: draft, sent, received, cancelled)
- notes (text, nullable)
- created_by (FK → users)
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
- quantity (decimal 10,2)
- unit_price (decimal 15,2)
- tax_rate (decimal 5,2)
- total (decimal 15,2)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model PurchaseOrderItem -mfs
```

---

```sql
-- inventory_logs table
- id (bigint, PK)
- company_id (FK → companies)
- product_id (FK → products)
- type (enum: in, out, adjustment)
- quantity (decimal 10,2)
- reference_type (string, nullable) -- polymorphic
- reference_id (bigint, nullable) -- polymorphic
- notes (text, nullable)
- created_by (FK → users)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model InventoryLog -mfs
```

---

### Phase 1.6: Accounting Module

```sql
-- invoices table
- id (bigint, PK)
- company_id (FK → companies)
- client_id (FK → clients)
- contract_id (FK → contracts, nullable)
- project_id (FK → projects, nullable)
- invoice_number (string, unique)
- invoice_date (date)
- due_date (date)
- subtotal (decimal 15,2)
- tax_amount (decimal 15,2)
- discount_amount (decimal 15,2)
- total (decimal 15,2)
- paid_amount (decimal 15,2, default: 0)
- status (enum: draft, sent, partially_paid, paid, overdue, cancelled)
- notes (text, nullable)
- created_by (FK → users)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Invoice -mfs
```

---

```sql
-- invoice_items table
- id (bigint, PK)
- invoice_id (FK → invoices)
- product_id (FK → products, nullable)
- description (text)
- quantity (decimal 10,2)
- unit_price (decimal 15,2)
- tax_rate (decimal 5,2)
- discount_rate (decimal 5,2)
- total (decimal 15,2)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model InvoiceItem -mfs
```

---

```sql
-- payments table
- id (bigint, PK)
- company_id (FK → companies)
- invoice_id (FK → invoices, nullable)
- payment_number (string, unique)
- payment_date (date)
- amount (decimal 15,2)
- payment_method (enum: cash, bank_transfer, cheque, credit_card, other)
- reference_number (string, nullable)
- notes (text, nullable)
- created_by (FK → users)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Payment -mfs
```

---

```sql
-- expenses table
- id (bigint, PK)
- company_id (FK → companies)
- category_id (FK → expense_categories)
- project_id (FK → projects, nullable)
- title (string)
- description (text, nullable)
- amount (decimal 15,2)
- expense_date (date)
- payment_method (enum: cash, bank_transfer, cheque, credit_card, other)
- receipt (string, nullable)
- status (enum: pending, approved, rejected, paid)
- created_by (FK → users)
- approved_by (FK → users, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Expense -mfs
```

---

```sql
-- expense_categories table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- description (text, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model ExpenseCategory -mfs
```

---

### Phase 1.7: HR Module

```sql
-- employees table (extends users)
- id (bigint, PK)
- user_id (FK → users, unique)
- employee_number (string, unique)
- job_title_id (FK → job_titles)
- hire_date (date)
- employment_type (enum: full_time, part_time, contract, intern)
- salary (decimal 15,2)
- salary_type (enum: monthly, hourly, daily)
- bank_account (string, nullable)
- emergency_contact_name (string, nullable)
- emergency_contact_phone (string, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Employee -mfs
```

---

```sql
-- job_titles table
- id (bigint, PK)
- company_id (FK → companies)
- title (string)
- description (text, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model JobTitle -mfs
```

---

```sql
-- attendances table
- id (bigint, PK)
- company_id (FK → companies)
- user_id (FK → users)
- date (date)
- check_in (time)
- check_out (time, nullable)
- work_hours (decimal 5,2, nullable)
- status (enum: present, late, absent, half_day)
- notes (text, nullable)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model Attendance -mfs
```

---

```sql
-- leaves table
- id (bigint, PK)
- company_id (FK → companies)
- user_id (FK → users)
- leave_type_id (FK → leave_types)
- start_date (date)
- end_date (date)
- days (integer)
- reason (text)
- status (enum: pending, approved, rejected)
- approved_by (FK → users, nullable)
- approved_at (timestamp, nullable)
- notes (text, nullable)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Leave -mfs
```

---

```sql
-- leave_types table
- id (bigint, PK)
- company_id (FK → companies)
- name (string)
- days_per_year (integer)
- paid (boolean, default: true)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model LeaveType -mfs
```

---

```sql
-- payrolls table
- id (bigint, PK)
- company_id (FK → companies)
- user_id (FK → users)
- month (integer)
- year (integer)
- basic_salary (decimal 15,2)
- allowances (decimal 15,2, default: 0)
- deductions (decimal 15,2, default: 0)
- net_salary (decimal 15,2)
- status (enum: draft, processed, paid)
- payment_date (date, nullable)
- notes (text, nullable)
- created_by (FK → users)
- created_at, updated_at, deleted_at
```

**Model Command:**
```bash
php artisan make:model Payroll -mfs
```

---

### Phase 1.8: Notifications & System Tables

```sql
-- notifications table (Laravel default)
- id (uuid, PK)
- type (string)
- notifiable_type (string)
- notifiable_id (bigint)
- data (text)
- read_at (timestamp, nullable)
- created_at, updated_at
```

---

```sql
-- activity_logs table
- id (bigint, PK)
- company_id (FK → companies)
- user_id (FK → users, nullable)
- subject_type (string)
- subject_id (bigint, nullable)
- action (string) -- created, updated, deleted, etc.
- description (text, nullable)
- properties (json, nullable)
- ip_address (string, nullable)
- user_agent (string, nullable)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model ActivityLog -mfs
```

---

```sql
-- settings table
- id (bigint, PK)
- company_id (FK → companies, nullable)
- key (string)
- value (text, nullable)
- type (enum: string, integer, boolean, json)
- created_at, updated_at
```

**Model Command:**
```bash
php artisan make:model Setting -mfs
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
php artisan make:controller Api/V1/CompanyController --api --model=Company
```

**Methods:**
- `index()` - قائمة الشركات
- `store()` - إضافة شركة
- `show()` - عرض شركة
- `update()` - تحديث شركة
- `destroy()` - حذف شركة

#### BranchController
```bash
php artisan make:controller Api/V1/BranchController --api --model=Branch
```

#### DepartmentController
```bash
php artisan make:controller Api/V1/DepartmentController --api --model=Department
```

#### UserController
```bash
php artisan make:controller Api/V1/UserController --api --model=User
```

**Additional Methods:**
- `assignRole()` - تعيين دور للمستخدم
- `removeRole()` - إزالة دور من المستخدم
- `updateStatus()` - تحديث حالة المستخدم

#### RoleController
```bash
php artisan make:controller Api/V1/RoleController --api --model=Role
```

**Additional Methods:**
- `assignPermissions()` - تعيين صلاحيات للدور
- `syncPermissions()` - مزامنة صلاحيات الدور

---

### Phase 2.3: CRM Controllers

#### ClientController
```bash
php artisan make:controller Api/V1/CRM/ClientController --api --model=Client
```

**Additional Methods:**
- `assignUser()` - تعيين مستخدم للعميل
- `updateStatus()` - تحديث حالة العميل
- `getContracts()` - عقود العميل
- `getInvoices()` - فواتير العميل

#### LeadController
```bash
php artisan make:controller Api/V1/CRM/LeadController --api --model=Lead
```

**Additional Methods:**
- `convertToClient()` - تحويل Lead إلى عميل
- `updateStatus()` - تحديث حالة Lead

#### OpportunityController
```bash
php artisan make:controller Api/V1/CRM/OpportunityController --api --model=Opportunity
```

**Additional Methods:**
- `updateProbability()` - تحديث احتمالية النجاح
- `convertToContract()` - تحويل إلى عقد

#### FollowUpController
```bash
php artisan make:controller Api/V1/CRM/FollowUpController --api --model=FollowUp
```

**Additional Methods:**
- `markAsCompleted()` - تحديد كمكتمل
- `getUpcoming()` - المتابعات القادمة

---

### Phase 2.4: Sales Controllers

#### QuotationController
```bash
php artisan make:controller Api/V1/Sales/QuotationController --api --model=Quotation
```

**Additional Methods:**
- `sendToClient()` - إرسال للعميل
- `accept()` - قبول العرض
- `reject()` - رفض العرض
- `convertToContract()` - تحويل لعقد
- `generatePDF()` - توليد PDF

#### ContractController
```bash
php artisan make:controller Api/V1/Sales/ContractController --api --model=Contract
```

**Additional Methods:**
- `activate()` - تفعيل العقد
- `complete()` - إنهاء العقد
- `cancel()` - إلغاء العقد
- `generatePDF()` - توليد PDF

---

### Phase 2.5: Projects Controllers

#### ProjectController
```bash
php artisan make:controller Api/V1/Projects/ProjectController --api --model=Project
```

**Additional Methods:**
- `updateProgress()` - تحديث التقدم
- `updateStatus()` - تحديث الحالة
- `assignManager()` - تعيين مدير
- `getStatistics()` - إحصائيات المشروع

#### ProjectPhaseController
```bash
php artisan make:controller Api/V1/Projects/ProjectPhaseController --api --model=ProjectPhase
```

#### TaskController
```bash
php artisan make:controller Api/V1/Projects/TaskController --api --model=Task
```

**Additional Methods:**
- `assignUsers()` - تعيين مستخدمين
- `updateProgress()` - تحديث التقدم
- `updateStatus()` - تحديث الحالة
- `addComment()` - إضافة تعليق

---

### Phase 2.6: Inventory Controllers

#### CategoryController
```bash
php artisan make:controller Api/V1/Inventory/CategoryController --api --model=Category
```

#### ProductController
```bash
php artisan make:controller Api/V1/Inventory/ProductController --api --model=Product
```

**Additional Methods:**
- `updateStock()` - تحديث المخزون
- `getLowStock()` - المنتجات منخفضة المخزون
- `getStockHistory()` - تاريخ المخزون

#### SupplierController
```bash
php artisan make:controller Api/V1/Inventory/SupplierController --api --model=Supplier
```

#### PurchaseOrderController
```bash
php artisan make:controller Api/V1/Inventory/PurchaseOrderController --api --model=PurchaseOrder
```

**Additional Methods:**
- `send()` - إرسال الطلب
- `receive()` - استلام الطلب
- `cancel()` - إلغاء الطلب

---

### Phase 2.7: Accounting Controllers

#### InvoiceController
```bash
php artisan make:controller Api/V1/Accounting/InvoiceController --api --model=Invoice
```

**Additional Methods:**
- `send()` - إرسال الفاتورة
- `recordPayment()` - تسجيل دفعة
- `cancel()` - إلغاء الفاتورة
- `generatePDF()` - توليد PDF

#### PaymentController
```bash
php artisan make:controller Api/V1/Accounting/PaymentController --api --model=Payment
```

#### ExpenseController
```bash
php artisan make:controller Api/V1/Accounting/ExpenseController --api --model=Expense
```

**Additional Methods:**
- `approve()` - الموافقة
- `reject()` - الرفض
- `markAsPaid()` - تحديد كمدفوع

---

### Phase 2.8: HR Controllers

#### EmployeeController
```bash
php artisan make:controller Api/V1/HR/EmployeeController --api --model=Employee
```

#### AttendanceController
```bash
php artisan make:controller Api/V1/HR/AttendanceController --api --model=Attendance
```

**Additional Methods:**
- `checkIn()` - تسجيل حضور
- `checkOut()` - تسجيل انصراف
- `getMonthlyReport()` - تقرير شهري

#### LeaveController
```bash
php artisan make:controller Api/V1/HR/LeaveController --api --model=Leave
```

**Additional Methods:**
- `approve()` - الموافقة
- `reject()` - الرفض
- `getBalance()` - رصيد الإجازات

#### PayrollController
```bash
php artisan make:controller Api/V1/HR/PayrollController --api --model=Payroll
```

**Additional Methods:**
- `process()` - معالجة الراتب
- `markAsPaid()` - تحديد كمدفوع
- `generatePayslip()` - توليد قسيمة الراتب

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
php artisan make:resource Api/V1/CompanyResource
php artisan make:resource Api/V1/BranchResource
php artisan make:resource Api/V1/DepartmentResource
php artisan make:resource Api/V1/UserResource
php artisan make:resource Api/V1/RoleResource
php artisan make:resource Api/V1/PermissionResource

# CRM Resources
php artisan make:resource Api/V1/CRM/ClientResource
php artisan make:resource Api/V1/CRM/LeadResource
php artisan make:resource Api/V1/CRM/OpportunityResource
php artisan make:resource Api/V1/CRM/FollowUpResource

# Sales Resources
php artisan make:resource Api/V1/Sales/QuotationResource
php artisan make:resource Api/V1/Sales/ContractResource

# Projects Resources
php artisan make:resource Api/V1/Projects/ProjectResource
php artisan make:resource Api/V1/Projects/TaskResource

# Inventory Resources
php artisan make:resource Api/V1/Inventory/ProductResource
php artisan make:resource Api/V1/Inventory/SupplierResource

# Accounting Resources
php artisan make:resource Api/V1/Accounting/InvoiceResource
php artisan make:resource Api/V1/Accounting/PaymentResource
php artisan make:resource Api/V1/Accounting/ExpenseResource

# HR Resources
php artisan make:resource Api/V1/HR/EmployeeResource
php artisan make:resource Api/V1/HR/AttendanceResource
php artisan make:resource Api/V1/HR/LeaveResource
php artisan make:resource Api/V1/HR/PayrollResource
```

---

### Phase 3.2: Form Requests (Validation)

#### Create Form Requests for All Controllers

```bash
# Auth Requests
php artisan make:request Api/V1/Auth/LoginRequest
php artisan make:request Api/V1/Auth/RegisterRequest
php artisan make:request Api/V1/Auth/ChangePasswordRequest

# Company Requests
php artisan make:request Api/V1/StoreCompanyRequest
php artisan make:request Api/V1/UpdateCompanyRequest

# CRM Requests
php artisan make:request Api/V1/CRM/StoreClientRequest
php artisan make:request Api/V1/CRM/UpdateClientRequest
php artisan make:request Api/V1/CRM/StoreLeadRequest
php artisan make:request Api/V1/CRM/UpdateLeadRequest

# Sales Requests
php artisan make:request Api/V1/Sales/StoreQuotationRequest
php artisan make:request Api/V1/Sales/UpdateQuotationRequest
php artisan make:request Api/V1/Sales/StoreContractRequest
php artisan make:request Api/V1/Sales/UpdateContractRequest

# Projects Requests
php artisan make:request Api/V1/Projects/StoreProjectRequest
php artisan make:request Api/V1/Projects/UpdateProjectRequest
php artisan make:request Api/V1/Projects/StoreTaskRequest
php artisan make:request Api/V1/Projects/UpdateTaskRequest

# Inventory Requests
php artisan make:request Api/V1/Inventory/StoreProductRequest
php artisan make:request Api/V1/Inventory/UpdateProductRequest

# Accounting Requests
php artisan make:request Api/V1/Accounting/StoreInvoiceRequest
php artisan make:request Api/V1/Accounting/UpdateInvoiceRequest
php artisan make:request Api/V1/Accounting/StoreExpenseRequest

# HR Requests
php artisan make:request Api/V1/HR/StoreEmployeeRequest
php artisan make:request Api/V1/HR/UpdateEmployeeRequest
php artisan make:request Api/V1/HR/StoreLeaveRequest
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
        
        // Users & Roles
        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::post('users/{user}/assign-role', [UserController::class, 'assignRole']);
        
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
            Route::apiResource('products', ProductController::class);
            Route::apiResource('suppliers', SupplierController::class);
            Route::apiResource('purchase-orders', PurchaseOrderController::class);
        });
        
        // Accounting Module
        Route::prefix('accounting')->group(function () {
            Route::apiResource('invoices', InvoiceController::class);
            Route::apiResource('payments', PaymentController::class);
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

# Role middleware
php artisan make:middleware CheckRole

# Permission middleware
php artisan make:middleware CheckPermission
```

**Register in `bootstrap/app.php`:**

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'company' => \App\Http\Middleware\EnsureUserBelongsToCompany::class,
        'role' => \App\Http\Middleware\CheckRole::class,
        'permission' => \App\Http\Middleware\CheckPermission::class,
    ]);
})
```

---

### Phase 4.3: Policies

```bash
# Create policies for all major models
php artisan make:policy CompanyPolicy --model=Company
php artisan make:policy ClientPolicy --model=Client
php artisan make:policy ProjectPolicy --model=Project
php artisan make:policy InvoicePolicy --model=Invoice
php artisan make:policy ExpensePolicy --model=Expense
```

---

### Phase 4.4: Seeders

```bash
# Create seeders
php artisan make:seeder RoleSeeder
php artisan make:seeder PermissionSeeder
php artisan make:seeder CompanySeeder
php artisan make:seeder UserSeeder
php artisan make:seeder ClientStatusSeeder
php artisan make:seeder LeadStatusSeeder
```

**Default Roles:**
- Super Admin
- Company Admin
- Manager
- Accountant
- HR Manager
- Employee

---

## 🚀 المرحلة الخامسة: Advanced Features

### Phase 5.1: Notifications System

```bash
# Create notification classes
php artisan make:notification NewLeadNotification
php artisan make:notification TaskAssignedNotification
php artisan make:notification InvoicePaidNotification
php artisan make:notification LeaveRequestNotification
php artisan make:notification ContractExpiringNotification
```

**Channels:** Database, Mail, SMS (optional)

---

### Phase 5.2: Events & Listeners

```bash
# Invoice Events
php artisan make:event InvoiceCreated
php artisan make:listener SendInvoiceToClient
php artisan make:listener UpdateAccountingRecords

# Task Events
php artisan make:event TaskAssigned
php artisan make:listener NotifyAssignedUsers

# Leave Events
php artisan make:event LeaveRequested
php artisan make:listener NotifyManager
```

---

### Phase 5.3: Jobs (Queue)

```bash
# Create jobs for heavy operations
php artisan make:job GenerateInvoicePDF
php artisan make:job SendBulkEmails
php artisan make:job ProcessMonthlyPayroll
php artisan make:job GenerateMonthlyReports
php artisan make:job BackupDatabase
```

---

### Phase 5.4: Observers

```bash
# Create observers for audit logging
php artisan make:observer ClientObserver --model=Client
php artisan make:observer InvoiceObserver --model=Invoice
php artisan make:observer ProjectObserver --model=Project
```

---

### Phase 5.5: Services Layer

**Create Service Classes for Business Logic:**

```
app/Services/
├── CRM/
│   ├── ClientService.php
│   ├── LeadService.php
│   └── OpportunityService.php
├── Sales/
│   ├── QuotationService.php
│   └── ContractService.php
├── Accounting/
│   ├── InvoiceService.php
│   └── PaymentService.php
└── Reports/
    └── ReportService.php
```

---

### Phase 5.6: PDF Generation

```bash
# Install DomPDF or similar
composer require barryvdh/laravel-dompdf
```

**Create PDF Templates:**
- Invoice PDF
- Quotation PDF
- Contract PDF
- Payslip PDF

---

### Phase 5.7: File Upload & Storage

**Configure Storage Disks in `config/filesystems.php`**

**Create Upload Handlers:**
- Company Logo
- User Avatar
- Expense Receipts
- Contract Documents
- Product Images

---

## ✅ المرحلة السادسة: Testing & Deployment

### Phase 6.1: Feature Tests

```bash
# Auth Tests
php artisan make:test --phpunit Auth/LoginTest
php artisan make:test --phpunit Auth/RegisterTest

# CRM Tests
php artisan make:test --phpunit CRM/ClientTest
php artisan make:test --phpunit CRM/LeadTest

# Sales Tests
php artisan make:test --phpunit Sales/QuotationTest
php artisan make:test --phpunit Sales/ContractTest

# Projects Tests
php artisan make:test --phpunit Projects/ProjectTest
php artisan make:test --phpunit Projects/TaskTest

# Accounting Tests
php artisan make:test --phpunit Accounting/InvoiceTest
php artisan make:test --phpunit Accounting/PaymentTest

# HR Tests
php artisan make:test --phpunit HR/AttendanceTest
php artisan make:test --phpunit HR/LeaveTest
```

**Run Tests:**
```bash
php artisan test --compact
```

---

### Phase 6.2: Code Quality

```bash
# Run Pint for code formatting
vendor/bin/pint --dirty

# Static Analysis (optional)
composer require --dev larastan/larastan
./vendor/bin/phpstan analyse
```

---

### Phase 6.3: API Documentation

**Options:**
1. **Scribe** (Recommended)
```bash
composer require --dev knuckleswtf/scribe
php artisan scribe:generate
```

2. **L5-Swagger**
```bash
composer require darkaonline/l5-swagger
```

---

### Phase 6.4: Performance Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Enable OPcache in production
```

**Database Optimization:**
- Add proper indexes
- Use eager loading to prevent N+1
- Implement query caching where appropriate

---

### Phase 6.5: Security Checklist

- [ ] CSRF Protection enabled
- [ ] SQL Injection prevention (use Eloquent/Query Builder)
- [ ] XSS Protection (escape output)
- [ ] Rate Limiting on API routes
- [ ] Input validation on all requests
- [ ] File upload validation
- [ ] Secure password hashing (bcrypt)
- [ ] HTTPS in production
- [ ] Environment variables secured
- [ ] Database credentials secured

---

### Phase 6.6: Deployment

**Deployment Checklist:**

1. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

2. **Database**
```bash
php artisan migrate --force
php artisan db:seed --force
```

3. **Storage**
```bash
php artisan storage:link
```

4. **Permissions**
```bash
chmod -R 775 storage bootstrap/cache
```

5. **Queue Worker** (if using queues)
```bash
php artisan queue:work --daemon
```

6. **Scheduler** (add to crontab)
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📊 Development Timeline Estimate

| Phase | Duration | Tasks |
|-------|----------|-------|
| **Phase 1: Database & Models** | 2-3 weeks | 40+ tables, migrations, models, factories, seeders |
| **Phase 2: Controllers** | 3-4 weeks | 30+ controllers with business logic |
| **Phase 3: APIs & Resources** | 2 weeks | API resources, form requests, routes |
| **Phase 4: Auth & Authorization** | 1 week | Sanctum, policies, middleware |
| **Phase 5: Advanced Features** | 3-4 weeks | Notifications, events, jobs, PDFs |
| **Phase 6: Testing & Deployment** | 2-3 weeks | Tests, documentation, optimization |
| **Total** | **13-17 weeks** | Full ERP system |

---

## 🎯 Priority Order (MVP First Approach)

### Sprint 1: Core Foundation (Week 1-2)
- [ ] Companies, Branches, Departments
- [ ] Users, Roles, Permissions
- [ ] Authentication (Login, Register)
- [ ] Basic Dashboard

### Sprint 2: CRM Module (Week 3-4)
- [ ] Clients Management
- [ ] Leads Management
- [ ] Follow-ups
- [ ] Client Statuses

### Sprint 3: Sales Module (Week 5-6)
- [ ] Quotations
- [ ] Contracts
- [ ] PDF Generation

### Sprint 4: Projects Module (Week 7-8)
- [ ] Projects
- [ ] Tasks
- [ ] Task Assignments

### Sprint 5: Accounting Module (Week 9-10)
- [ ] Invoices
- [ ] Payments
- [ ] Expenses

### Sprint 6: Inventory Module (Week 11-12)
- [ ] Products
- [ ] Categories
- [ ] Purchase Orders
- [ ] Stock Management

### Sprint 7: HR Module (Week 13-14)
- [ ] Employees
- [ ] Attendance
- [ ] Leaves
- [ ] Payroll

### Sprint 8: Polish & Deploy (Week 15-17)
- [ ] Notifications
- [ ] Reports
- [ ] Testing
- [ ] Documentation
- [ ] Deployment

---

## 📝 Notes & Best Practices

### Multi-Tenancy Implementation
- كل جدول يحتوي على `company_id`
- استخدم Global Scopes للتصفية التلقائية
- Middleware للتحقق من انتماء المستخدم للشركة

### Soft Deletes
- استخدم `SoftDeletes` في جميع الجداول الرئيسية
- يسمح باسترجاع البيانات المحذوفة

### Audit Logging
- سجل جميع العمليات المهمة
- استخدم Observers أو Events

### API Versioning
- ابدأ بـ `/api/v1`
- سهل الترقية للإصدارات المستقبلية

### Validation
- استخدم Form Requests دائماً
- لا تضع Validation في Controllers

### Code Organization
- استخدم Services للـ Business Logic
- Controllers تكون رفيعة (Thin Controllers)
- Models تحتوي على Relationships فقط

---

## 🔧 Useful Commands Reference

```bash
# Create complete model with migration, factory, seeder
php artisan make:model ModelName -mfs

# Create API controller
php artisan make:controller Api/V1/ControllerName --api --model=ModelName

# Create resource
php artisan make:resource Api/V1/ResourceName

# Create form request
php artisan make:request StoreModelRequest

# Create policy
php artisan make:policy ModelPolicy --model=ModelName

# Create test
php artisan make:test --phpunit ModelTest

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Run tests
php artisan test --compact

# Format code
vendor/bin/pint --dirty

# Clear all caches
php artisan optimize:clear
```

---

## 📚 Additional Resources

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [RESTful API Design](https://restfulapi.net/)

---

## ✨ Final Checklist

- [ ] All migrations created and tested
- [ ] All models with relationships
- [ ] All controllers with CRUD operations
- [ ] All API resources created
- [ ] All form requests with validation
- [ ] Authentication working (Sanctum)
- [ ] Authorization implemented (Policies)
- [ ] Multi-tenancy working
- [ ] Notifications system
- [ ] PDF generation
- [ ] File uploads
- [ ] Tests written and passing
- [ ] Code formatted with Pint
- [ ] API documentation generated
- [ ] Deployment ready

---

**Good Luck! 🚀**

هذا الـ Roadmap شامل لكل خطوة في المشروع. ابدأ بالترتيب واتبع الـ Sprints للحصول على نتائج سريعة!
