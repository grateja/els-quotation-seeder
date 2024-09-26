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
        Schema::create('product_bullet_descriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('product_id');
            $table->integer('stack_order');
            $table->text('description');

            $table->double('quantity')->nullable();
            $table->string('unit')->nullable()->remarks('PCS.,LOT,UNIT,BOX,CARBOY,LITER,MEGAGALLON');

            $table->string('bullet_type')->default('simple')
                ->remarks('simple,item');

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bullet_descriptions');
    }
};
