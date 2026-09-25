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
        Schema::create('payroll_reports', function (Blueprint $table) {
            $table->id();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_regular_hours', 12, 2)->default(0);
            $table->decimal('total_overtime_hours', 12, 2)->default(0);
            $table->decimal('total_gross_pay', 14, 2)->default(0);
            $table->string('status')->default('draft');
            $table->foreignId('generated_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_reports');
    }
};
