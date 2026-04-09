<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update financial_records table
        Schema::table('financial_records', function (Blueprint $table) {
            if (!Schema::hasColumn('financial_records', 'status')) {
                $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed')->after('type');
            }
            if (!Schema::hasColumn('financial_records', 'reference_no')) {
                $table->string('reference_no')->nullable()->after('id');
            }
            if (!Schema::hasColumn('financial_records', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete()->after('company_id');
            }
        });

        // 2. Update customers table
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'customer_number')) {
                $table->string('customer_number')->nullable()->after('id');
            }
            if (!Schema::hasColumn('customers', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('name');
            }
            if (!Schema::hasColumn('customers', 'status')) {
                $table->enum('status', ['active', 'inactive', 'lead'])->default('active')->after('email');
            }
        });

        // 3. Update products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'product_code')) {
                $table->string('product_code')->nullable()->after('id');
            }
            if (!Schema::hasColumn('products', 'status')) {
                $table->enum('status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock')->after('price');
            }
            if (!Schema::hasColumn('products', 'stock_quantity')) {
                $table->integer('stock_quantity')->default(0)->after('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_records', function (Blueprint $table) {
            $table->dropColumn(['status', 'reference_no']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['customer_number', 'contact_person', 'status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_code', 'status', 'stock_quantity']);
        });
    }
};
