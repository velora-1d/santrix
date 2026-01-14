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
        // Option 1: Modify status enum to include 'trial'
        // Option 2: Change to string for flexibility
        // We will change it to string (varchar) to prevent future enum issues
        
        Schema::table('pesantrens', function (Blueprint $table) {
            $table->string('status', 20)->default('trial')->change();
            $table->string('package', 20)->default('basic-3')->change(); // Update package to support 'basic-3', 'advance-3' etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting is risky if we have data that doesn't fit the old enum.
        // We'll keep it as string for safety in down() as well, or just try to revert to enum.
        
        Schema::table('pesantrens', function (Blueprint $table) {
            // $table->enum('status', ['active', 'suspended', 'inactive', 'trial'])->change(); // Try to revert if valid
        });
    }
};
