<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->enum('type', ['string', 'number', 'json', 'boolean'])->default('string');
            $table->string('group')->default('general')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed initial landing page stats
        DB::table('settings')->insert([
            [
                'key' => 'landing_stats_pesantren',
                'value' => '120',
                'type' => 'number',
                'group' => 'landing_page',
                'description' => 'Jumlah pesantren yang ditampilkan di landing page',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'landing_stats_santri',
                'value' => '69000',
                'type' => 'number',
                'group' => 'landing_page',
                'description' => 'Jumlah santri yang ditampilkan di landing page',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'landing_stats_users',
                'value' => '480',
                'type' => 'number',
                'group' => 'landing_page',
                'description' => 'Jumlah pengguna yang ditampilkan di landing page',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
