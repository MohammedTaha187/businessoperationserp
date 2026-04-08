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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('payable'); // payable_id, payable_type (Invoice, Order)
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('EGP');
            $table->string('provider'); // paymob, stripe, etc.
            $table->string('provider_id')->unique(); // ID from the provider
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('extra_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
