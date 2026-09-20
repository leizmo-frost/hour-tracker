<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reports_to')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('employee_code')->unique();
            $table->date('hire_date')->nullable();
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->integer('leadership_level')->default(0);
            $table->timestamps();

            $table->index(['company_id', 'reports_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
