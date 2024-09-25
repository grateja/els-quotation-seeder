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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->double('unit_price')->default(0);
            $table->string('currency_code')->default('PHP');
            $table->uuid('product_line_id')->nullable();

            $table->timestamps();

            $table->foreign('product_line_id')->references('id')->on('product_lines')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
