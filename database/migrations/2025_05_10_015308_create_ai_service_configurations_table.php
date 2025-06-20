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
        Schema::create('ai_service_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Descriptive name for the configuration, e.g., "OpenAI for Anonymization"');
            $table->string('service_type')->index()->comment('Type of service: anonymization, analysis, etc.');
            $table->string('provider')->index()->comment('AI provider: openai, ollama, etc.');
            $table->string('model');
            $table->string('base_url')->nullable();
            $table->string('api_key_env_var')->nullable()->comment('Environment variable name holding the API key');
            $table->integer('timeout_seconds')->default(30);
            $table->integer('max_chars_per_batch')->nullable();
            $table->float('temperature', 8, 2)->nullable();
            $table->integer('max_tokens')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->json('meta_config')->nullable()->comment('Additional provider-specific configurations');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Optional: if you want to soft delete configurations
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_service_configurations');
    }
};
