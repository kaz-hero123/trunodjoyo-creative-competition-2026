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
        Schema::dropIfExists('resource_matches');
        Schema::dropIfExists('resources');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not intended to be reversed as it's part of PRDv3 cleanup
    }
};
