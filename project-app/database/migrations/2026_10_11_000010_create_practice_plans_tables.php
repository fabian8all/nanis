<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_plans', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_request_id')->unique()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('practice_plan_versions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_plan_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->string('status', 40);
            $table->timestamp('submitted_at');
            $table->foreignId('supervisor_reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('supervisor_reviewed_at')->nullable();
            $table->text('supervisor_review_comment')->nullable();
            $table->foreignId('coordinator_reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('coordinator_reviewed_at')->nullable();
            $table->text('coordinator_review_comment')->nullable();
            $table->timestamps();
            $table->unique(['practice_plan_id', 'version']);
        });

        Schema::create('practice_plan_activities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_plan_version_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('position');
            $table->text('description');
            $table->string('responsible');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('expected_result');
            $table->timestamps();
            $table->unique(
                ['practice_plan_version_id', 'position'],
                'plan_activity_version_position_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_plan_activities');
        Schema::dropIfExists('practice_plan_versions');
        Schema::dropIfExists('practice_plans');
    }
};
