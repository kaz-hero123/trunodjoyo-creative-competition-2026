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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['scholarship', 'counseling', 'academic_support', 'community', 'career']);
            $table->string('title');
            $table->text('description');
            $table->string('provider_name');
            $table->string('url')->nullable();
            $table->string('contact_info')->nullable();
            $table->date('deadline')->nullable();
            $table->json('target_dimensions');
            $table->unsignedTinyInteger('min_semester')->default(1);
            $table->unsignedTinyInteger('max_semester')->default(14);
            $table->json('eligible_majors')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
