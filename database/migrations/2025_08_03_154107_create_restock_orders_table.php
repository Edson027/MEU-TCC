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
        Schema::create('restock_orders', function (Blueprint $table) {
              $table->id();
    $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
    $table->integer('quantity_requested');
    $table->string('status')->default('pending'); // pending, approved, rejected
    $table->text('rejection_reason')->nullable();
    $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
    $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_orders');
    }
};
