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
        Schema::create('breakdown_reports', function (Blueprint $table) {
            $table->id();

            $table->string('report_id')->unique();
            $table->string('bus_id', 6);
            $table->string('user_id', 6);

            $table->date('report_date');
            $table->time('report_time');
            $table->string('place');
            $table->text('description');

            $table->json('pics')->nullable();

            $table->string('status')->default('Pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdown_reports');
    }
};
