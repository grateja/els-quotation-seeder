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
        Schema::create('product_lines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->uuid('sales_representative_id')->nullable();
            $table->timestamps();

            $table->foreign('sales_representative_id')->references('id')->on('sales_representatives')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_lines');
    }
};
