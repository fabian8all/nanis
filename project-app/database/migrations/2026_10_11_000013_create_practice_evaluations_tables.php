<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_evaluations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_request_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('practice_report_version_id')->constrained('practice_report_versions')->restrictOnDelete();
            $table->foreignId('rubric_version_id')->constrained()->restrictOnDelete();
            $table->string('status', 30)->index();
            $table->decimal('score_total', 5, 2)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('practice_evaluation_criteria', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_evaluation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rubric_criterion_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('position');
            $table->decimal('weight', 5, 2);
            $table->json('scale');
            $table->decimal('score', 8, 2)->nullable();
            $table->string('score_label')->nullable();
            $table->decimal('contribution', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['practice_evaluation_id', 'rubric_criterion_id'], 'practice_evaluation_criterion_unique');
            $table->unique(['practice_evaluation_id', 'position'], 'practice_evaluation_position_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_evaluation_criteria');
        Schema::dropIfExists('practice_evaluations');
    }
};
