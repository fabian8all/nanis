<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_acceptance_submissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supervisor_id')->constrained('users')->restrictOnDelete();
            $table->unsignedSmallInteger('version');
            $table->string('file_path');
            $table->string('file_extension', 10);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->string('status')->default('pending_review');
            $table->text('review_comment')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['practice_request_id', 'version'], 'acceptance_request_version_unique');
            $table->index(['status', 'submitted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_acceptance_submissions');
    }
};
