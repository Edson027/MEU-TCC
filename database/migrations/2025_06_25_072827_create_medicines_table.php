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
        Schema::create('medicines', function (Blueprint $table) {
                 $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->string('batch')->nullable();
    $table->date('expiration_date');
    $table->integer('stock')->default(0);
    $table->decimal('price', 8, 2)->nullable();
    $table->string('category')->nullable();
       $table->integer('minimum_stock')->default(10);
    $table->timestamps();
      $table->softDeletes();
       $table->timestamp('last_alerted_at')->nullable();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
