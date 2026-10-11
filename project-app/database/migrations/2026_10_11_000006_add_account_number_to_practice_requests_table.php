<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('practice_requests', function (Blueprint $table): void {
            $table->text('account_number')->nullable()->after('nss');
        });
    }

    public function down(): void
    {
        Schema::table('practice_requests', function (Blueprint $table): void {
            $table->dropColumn('account_number');
        });
    }
};
