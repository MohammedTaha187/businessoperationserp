# 🏢 Business Operations ERP

[![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Search](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

**Professional B2B ERP Platform built with a modern API-First architecture.**

---

## 📋 About the Project
A comprehensive, enterprise-grade ERP system tailored for industrial supply and service companies. Built with **Laravel 12**, it features multi-tenant data isolation, advanced security, and seamless financial integrations.

### Key Highlights
- **Architecture**: Service-Layer & Repository Pattern for clean logic.
- **Enterprise Features**: Multilingual support, professional reporting (PDF/Excel), and robust media management.
- **Security**: Granular RBAC, stateless API authentication, and 6-digit OTP verification.
- **Fintech**: Integrated with Stripe, PayPal, Paymob, and Kashier.

---

## 🚀 Main Modules
- **CRM**: Customers, Leads, Interaction Notes, and Opportunities.
- **Inventory**: Category management, Multi-branch Stock tracking, and Automated alerts.
- **Finances**: Multi-gateway Payments, Invoicing, Wallets, and Transaction journaling.
- **HR Layer**: Employee management, Salary calculations, and Notifications.

---

## 🛠️ Quick Start

### 1. Prerequisites
- PHP 8.4+
- Composer
- MySQL

### 2. Setup
```bash
# Clone the repository
git clone <repository-url>
cd businessoperationserp

# Install dependencies
composer install
npm install

# Setup Environment
cp .env.example .env
php artisan key:generate
```
*Update your `.env` with DB and Mail credentials.*

### 3. Initialize Database
```bash
php artisan migrate --seed
```

### 4. Serve
```bash
php artisan serve
```

---

## 🔑 Default Credentials
After seeding, you can log in with:
- **Email**: `admin@erp.com`
- **Password**: `password`
- **Role**: `Super Admin`

---

## 📚 Documentation
- 🗺️ **[ROADMAP.md](./ROADMAP.md)**: Detailed phase-by-phase development plan (English).
- 📊 **[ROADMAP_AR.md](./ROADMAP_AR.md)**: Original Arabic documentation.
- 🔌 **[Postman Collection](./postman_collection.json)**: API testing suite.

---

<div align="center">
Built with ❤️ by the ERP Team
</div>
