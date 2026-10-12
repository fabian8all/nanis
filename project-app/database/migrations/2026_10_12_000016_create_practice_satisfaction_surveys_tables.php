<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_survey_versions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('version')->unique();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('practice_survey_questions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_survey_version_id')->constrained()->cascadeOnDelete();
            $table->string('question', 500);
            $table->unsignedInteger('position');
            $table->boolean('allow_comment')->default(false);
            $table->timestamps();
            $table->unique(['practice_survey_version_id', 'position'], 'practice_survey_question_position_unique');
        });

        Schema::create('practice_survey_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_request_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('practice_acceptance_submission_id');
            $table->foreign('practice_acceptance_submission_id', 'practice_survey_acceptance_fk')
                ->references('id')
                ->on('practice_acceptance_submissions')
                ->restrictOnDelete();
            $table->foreignId('supervisor_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('practice_survey_version_id')->constrained()->restrictOnDelete();
            $table->string('status', 20)->default('requested')->index();
            $table->foreignId('requested_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('requested_at');
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['supervisor_id', 'status'], 'practice_survey_request_supervisor_status_index');
        });

        Schema::create('practice_survey_answers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_survey_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('practice_survey_question_id')->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(
                ['practice_survey_request_id', 'practice_survey_question_id'],
                'practice_survey_answer_question_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_survey_answers');
        Schema::dropIfExists('practice_survey_requests');
        Schema::dropIfExists('practice_survey_questions');
        Schema::dropIfExists('practice_survey_versions');
    }
};
