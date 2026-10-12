<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_supervisor_evaluations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_request_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('practice_acceptance_submission_id');
            $table->foreign('practice_acceptance_submission_id', 'ps_eval_acceptance_submission_fk')
                ->references('id')
                ->on('practice_acceptance_submissions')
                ->restrictOnDelete();
            $table->foreignId('supervisor_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('rubric_version_id')->constrained()->restrictOnDelete();
            $table->string('status', 30)->index();
            $table->decimal('score_total', 5, 2)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('practice_supervisor_evaluation_criteria', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_supervisor_evaluation_id');
            $table->foreign('practice_supervisor_evaluation_id', 'ps_eval_criteria_evaluation_fk')
                ->references('id')
                ->on('practice_supervisor_evaluations')
                ->cascadeOnDelete();
            $table->foreignId('rubric_criterion_id');
            $table->foreign('rubric_criterion_id', 'ps_eval_criteria_rubric_fk')
                ->references('id')
                ->on('rubric_criteria')
                ->restrictOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('position');
            $table->decimal('weight', 5, 2);
            $table->json('scale');
            $table->decimal('score', 8, 2)->nullable();
            $table->string('score_label')->nullable();
            $table->decimal('contribution', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(
                ['practice_supervisor_evaluation_id', 'rubric_criterion_id'],
                'practice_supervisor_evaluation_criterion_unique',
            );
            $table->unique(
                ['practice_supervisor_evaluation_id', 'position'],
                'practice_supervisor_evaluation_position_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_supervisor_evaluation_criteria');
        Schema::dropIfExists('practice_supervisor_evaluations');
    }
};
