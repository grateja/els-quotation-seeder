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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('crn')
                ->remarks('customer reference number')
                ->unique();

            $table->string('name');
            $table->string('address');
            $table->string('contact_number')->nullable();

            $table->uuid('sales_representative_id')->nullable();

            $table->foreign('sales_representative_id')->references('id')->on('sales_representatives')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
