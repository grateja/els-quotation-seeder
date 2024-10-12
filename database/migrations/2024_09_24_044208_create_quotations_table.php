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
        Schema::create('quotations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('quotation_number')->unique();

            $table->uuid('user_id')->remarks('prepared by');
            $table->uuid('subdealer_id')->nullable();
            $table->uuid('sales_representative_id')->nullable();

            $table->uuid('customer_id');

            $table->string('status')->default('draft')->remarks('draft,closed,under-nego,lost-sales,follow-up,no-site');

            $table->timestamps();


            $table->foreign('sales_representative_id')->references('id')->on('sales_representatives')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('subdealer_id')->references('id')->on('subdealers')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
