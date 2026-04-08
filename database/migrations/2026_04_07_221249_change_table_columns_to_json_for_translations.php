<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Companies
        DB::table('companies')->get()->each(function ($row) {
            DB::table('companies')->where('id', $row->id)->update([
                'name' => json_encode(['ar' => $row->name, 'en' => $row->name]),
            ]);
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->json('name')->change();
        });

        // Categories
        DB::table('categories')->get()->each(function ($row) {
            DB::table('categories')->where('id', $row->id)->update([
                'name' => json_encode(['ar' => $row->name, 'en' => $row->name]),
            ]);
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->json('name')->change();
        });

        // Products
        DB::table('products')->get()->each(function ($row) {
            DB::table('products')->where('id', $row->id)->update([
                'name' => json_encode(['ar' => $row->name, 'en' => $row->name]),
                'description' => json_encode(['ar' => $row->description, 'en' => $row->description]),
            ]);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->nullable()->change();
        });
    }
};
