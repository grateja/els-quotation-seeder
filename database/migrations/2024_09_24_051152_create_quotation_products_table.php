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
        Schema::create('quotation_products', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('quotation_id');
            $table->uuid('product_id');

            $table->double('unit_price');
            $table->string('currency_code');
            $table->integer('quantity');
            $table->double('discount')->remarks('in peso');

            $table->timestamps();

            $table->foreign('quotation_id')->references('id')->on('quotations')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_products');
    }
};
