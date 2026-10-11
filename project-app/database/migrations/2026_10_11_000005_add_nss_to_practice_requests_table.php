<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('practice_requests', function (Blueprint $table): void {
            $table->text('nss')->nullable()->after('ucol_email');
        });
    }

    public function down(): void
    {
        Schema::table('practice_requests', function (Blueprint $table): void {
            $table->dropColumn('nss');
        });
    }
};
