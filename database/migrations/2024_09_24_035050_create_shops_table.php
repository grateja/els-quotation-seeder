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
        Schema::create('shops', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->uuid('customer_id');
            $table->text('address');

            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
