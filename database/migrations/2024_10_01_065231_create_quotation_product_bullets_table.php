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
        Schema::create('quotation_product_bullets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('quotation_product_id');
            $table->text('description');
            $table->string('bullet_type')->default('simple')
                ->remarks('simple,item');

            $table->timestamps();

            $table->foreign('quotation_product_id')->references('id')->on('quotation_products')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_product_bullets');
    }
};
