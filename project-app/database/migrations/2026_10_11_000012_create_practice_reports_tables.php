<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('practice_requests', function (Blueprint $table): void {
            $table->foreignId('tutor_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
        });

        Schema::create('practice_reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_request_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('approved_version_id')->nullable();
            $table->timestamps();
        });

        Schema::create('practice_report_versions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_report_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->string('status', 40);
            $table->string('company_name', 255);
            $table->string('area', 255);
            $table->string('period', 255);
            $table->longText('objectives');
            $table->longText('activities');
            $table->longText('outcomes');
            $table->longText('conclusions');
            $table->longText('recommendations');
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_comment')->nullable();
            $table->json('section_comments')->nullable();
            $table->timestamps();
            $table->unique(['practice_report_id', 'version']);
        });

        Schema::table('practice_reports', function (Blueprint $table): void {
            $table->foreign('approved_version_id')
                ->references('id')
                ->on('practice_report_versions')
                ->nullOnDelete();
        });

        Schema::create('practice_report_attachments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_report_version_id')->constrained()->cascadeOnDelete();
            $table->string('file_path')->index();
            $table->string('original_name');
            $table->string('mime_type', 127);
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_report_attachments');
        Schema::table('practice_reports', function (Blueprint $table): void {
            $table->dropForeign(['approved_version_id']);
        });
        Schema::dropIfExists('practice_report_versions');
        Schema::dropIfExists('practice_reports');
        Schema::table('practice_requests', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('tutor_id');
        });
    }
};
