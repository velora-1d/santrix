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
        Schema::create('pesantrens', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('subdomain')->unique()->index();
            $table->string('domain')->nullable()->unique();
            $table->enum('package', ['basic', 'advance'])->default('basic');
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active');
            $table->date('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesantrens');
    }
};
