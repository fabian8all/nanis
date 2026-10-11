<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_request_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coordinator_id')->constrained('users')->restrictOnDelete();
            $table->string('decision');
            $table->text('comment')->nullable();
            $table->timestamp('reviewed_at');
            $table->timestamps();

            $table->index(['practice_request_id', 'reviewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_request_reviews');
    }
};
