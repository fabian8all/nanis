<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->string('ucol_email');
            $table->string('grade_group');
            $table->date('start_date');
            $table->string('practice_location');
            $table->string('letter_recipient');
            $table->string('position');
            $table->date('end_date');
            $table->string('status')->default('pending');
            $table->timestamp('non_responsiva_accepted_at');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_requests');
    }
};
