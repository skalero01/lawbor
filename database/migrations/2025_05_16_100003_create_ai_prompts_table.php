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
        Schema::create('ai_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('service_type'); // 'anonymization', 'analysis', etc.
            $table->string('prompt_type'); // 'standard', 'chunk', 'combination', etc.
            $table->string('name');
            $table->text('content');
            $table->json('analysis_fields')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Índices para búsquedas comunes
            $table->index(['service_type', 'prompt_type']);
            $table->index(['is_active', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_prompts');
    }
};
