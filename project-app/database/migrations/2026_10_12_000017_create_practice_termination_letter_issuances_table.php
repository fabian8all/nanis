<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_termination_letter_issuances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_request_id');
            $table->foreignId('issued_by');
            $table->timestamp('issued_at');
            $table->timestamps();

            $table->unique('practice_request_id', 'ptli_request_unique');
            $table->foreign('practice_request_id', 'ptli_request_fk')
                ->references('id')
                ->on('practice_requests')
                ->cascadeOnDelete();
            $table->foreign('issued_by', 'ptli_issued_by_fk')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_termination_letter_issuances');
    }
};
