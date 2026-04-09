# 🏢 Business Operations ERP

<div align="center">
  <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel Logo" width="100">
  <br />
  <h3>Enterprise-Grade B2B ERP Platform</h3>
  <p><i>API-First Architecture | High Performance | Scalable Business Solutions</i></p>

  [![Laravel 12+](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![PHP 8.4+](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![MySQL 8.4](https://img.shields.io/badge/MySQL-8.4-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
  [![Redis](https://img.shields.io/badge/Redis-6.x-DC382D?style=for-the-badge&logo=redis&logoColor=white)](https://redis.io)
  [![Docker](https://img.shields.io/badge/Docker-Sail-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
</div>

---

## 📋 Table of Contents
- [About the Project](#-about-the-project)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Installation (Using Sail)](#-installation-using-sail)
- [Main Modules](#-main-modules)
- [Documentation](#-documentation)

---

## 🌟 About the Project
**Business Operations ERP** is a robust, production-ready ERP system designed for industrial and commercial sectors. It follows modern design patterns including **Service-Layer Architecture** and **Repository Pattern** to ensure high maintainability and testability.

---

## ✨ Key Features
- **🚀 Dockerized Environment**: Fully integrated with **Laravel Sail** for a seamless development experience.
- **🖼️ Profile Management**: Advanced user profile support with dedicated image processing and storage linking.
- **🛡️ Enterprise Security**: Granular RBAC (Spatie), Stateless API Authentication (Sanctum), and 6-digit OTP verification.
- **💰 Fintech Ready**: Integration with Stripe, PayPal, Paymob, and Kashier.
- **🌍 Bilingual & Multilingual**: Built-in support for multiple languages including Arabic.
- **📊 Real-time Inventory**: Multi-branch stock tracking with automated transaction journaling.
- **🛠️ Developer Experience**: Customized system aliases for faster command execution (`sa`, `sm`, `sms`, etc.).

---

## 🛠️ Tech Stack

| Category | Technologies |
| :--- | :--- |
| **Core Framework** | ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white) ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white) |
| **Database & Cache** | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white) ![Redis](https://img.shields.io/badge/Redis-DC382D?style=flat-square&logo=redis&logoColor=white) |
| **DevOps** | ![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat-square&logo=docker&logoColor=white) ![Laravel Sail](https://img.shields.io/badge/Sail-white?style=flat-square&logo=laravel&logoColor=red) |
| **Packages** | ![Sanctum](https://img.shields.io/badge/Sanctum-shield?style=flat-square) ![Spatie](https://img.shields.io/badge/Spatie-media_library-blue?style=flat-square) |
| **Tools** | ![Postman](https://img.shields.io/badge/Postman-FF6C37?style=flat-square&logo=postman&logoColor=white) ![phpMyAdmin](https://img.shields.io/badge/phpMyAdmin-6C78AF?style=flat-square&logo=phpmyadmin&logoColor=white) |

---

## 🚀 Installation (Using Sail)

The project is optimized for **Laravel Sail**. Follow these steps to get up and running:

### 1. Requirements
Ensure you have **Docker Desktop** installed on your machine.

### 2. Setup
```bash
# Clone the repository
git clone <repository-url>
cd businessoperationserp

# Install Composer dependencies via Docker
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php85-composer:latest \
    composer install --ignore-platform-reqs

# Prepare Environment
cp .env.example .env
php artisan key:generate
```

### 3. Launching Containers
```bash
./vendor/bin/sail up -d
```
*Wait for containers to be healthy. Access **phpMyAdmin** at `http://localhost:8081`.*

### 4. Initialize Database
```bash
# Run migrations and seed baseline data
./vendor/bin/sail artisan migrate --seed

# Fix Storage Permissions
./vendor/bin/sail artisan storage:link
```

---

## ⌨️ Custom Developer Aliases
To speed up your development, add these to your `~/.bashrc` or `~/.zshrc`:
```bash
alias sail='./vendor/bin/sail'
alias sa='sail artisan'
alias sm='sail artisan migrate'
alias sms='sail artisan migrate:fresh --seed'
```

---

## 🚀 Main Modules
- **CRM**: Customers, Leads, Interaction Notes, and Opportunities.
- **Inventory**: Category management, Multi-branch Stock tracking, and Automated alerts.
- **Finances**: Multi-gateway Payments, Invoicing, Wallets, and Transaction journaling.
- **HR Layer**: Employee management, Salary calculations, and Notifications.

---

## 📚 Documentation
- 🗺️ **[ROADMAP.md](./ROADMAP.md)**: Detailed phase-by-phase development plan.
- 📊 **[ROADMAP_AR.md](./ROADMAP_AR.md)**: Arabic translation of development goals.
- 🔌 **[Postman Collection](./postman_collection.json)**: Full API documentation and testing suite.

---

<div align="center">
  <b>Built with Modern Standards for Professional Enterprise Operations</b><br />
  &copy; 2026 ERP Team
</div>
