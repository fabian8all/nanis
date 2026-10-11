<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practice_letters', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('practice_request_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('coordinator_id')->constrained('users')->restrictOnDelete();
            $table->unsignedSmallInteger('folio_year');
            $table->unsignedInteger('folio_number');
            $table->string('folio')->unique();
            $table->string('letter_recipient');
            $table->string('position');
            $table->string('practice_location');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamp('issued_at');
            $table->timestamps();

            $table->unique(['folio_year', 'folio_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_letters');
    }
};
