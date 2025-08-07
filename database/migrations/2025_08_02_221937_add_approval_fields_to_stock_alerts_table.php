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
        Schema::table('stock_alerts', function (Blueprint $table) {
              $table->integer('approved_quantity')->nullable()->after('quantity');
            $table->text('approval_notes')->nullable()->after('approved_quantity');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('approval_notes');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            
            $table->text('rejection_reason')->nullable()->after('approved_at');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->after('rejection_reason');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_alerts', function (Blueprint $table) {
             $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            
            $table->dropColumn([
                'approved_quantity',
                'approval_notes',
                'approved_by',
                'approved_at',
                'rejection_reason',
                'rejected_by',
                'rejected_at'
            ]);
        });
    }
};
