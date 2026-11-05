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
        Schema::table('breakdown_reports', function (Blueprint $table) {
            $table->string('assigned_mechanic_id', 6)->nullable();
            $table->string('approved_by', 6)->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breakdown_reports', function (Blueprint $table) {
            $table->dropColumn([
                'assigned_mechanic_id',
                'approved_by',
                'approved_at',
                'rejection_reason',
            ]);
        });
    }
};
