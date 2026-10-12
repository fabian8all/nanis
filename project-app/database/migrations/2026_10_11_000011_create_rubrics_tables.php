<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rubrics', function (Blueprint $table): void {
            $table->id();
            $table->string('type')->unique();
            $table->unsignedBigInteger('current_version_id')->nullable();
            $table->timestamps();
        });
        Schema::create('rubric_versions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('rubric_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['rubric_id', 'version']);
            $table->index(['rubric_id', 'status']);
        });
        Schema::table('rubrics', function (Blueprint $table): void {
            $table->foreign('current_version_id')->references('id')->on('rubric_versions')->nullOnDelete();
        });
        Schema::create('rubric_criteria', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('rubric_version_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('position');
            $table->decimal('weight', 5, 2)->default(0);
            $table->json('scale');
            $table->timestamps();
            $table->unique(['rubric_version_id', 'position']);
            $table->index('rubric_version_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubric_criteria');
        Schema::table('rubrics', fn (Blueprint $table) => $table->dropForeign(['current_version_id']));
        Schema::dropIfExists('rubric_versions');
        Schema::dropIfExists('rubrics');
    }
};
