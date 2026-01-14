<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('talaran_santri', function (Blueprint $table) {
            $table->unsignedBigInteger('pesantren_id')->nullable()->after('id');
            $table->index('pesantren_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talaran_santri', function (Blueprint $table) {
            $table->dropIndex(['pesantren_id']);
            $table->dropColumn('pesantren_id');
        });
    }
};

