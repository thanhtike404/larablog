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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Laravel", "Vue.js", "React", "Docker"
            $table->string('slug')->unique(); // e.g., "laravel", "vue-js", "react", "docker"
            $table->string('color')->nullable(); // hex color for UI display
            $table->text('description')->nullable(); // optional description of the technology
            $table->string('official_url')->nullable(); // link to official documentation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
