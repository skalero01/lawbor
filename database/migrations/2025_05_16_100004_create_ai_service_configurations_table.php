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
        Schema::dropIfExists('ai_service_configurations');
        Schema::create('ai_service_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('ai_providers');
            $table->string('service_type'); // 'anonymization', 'analysis', etc.
            $table->string('name');
            $table->string('model');
            $table->integer('timeout_seconds')->default(600);
            $table->integer('max_chars_per_batch')->default(2000);
            $table->float('temperature')->default(0.7);
            $table->integer('max_tokens')->default(2000);
            $table->json('service_parameters')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            // Índices para búsquedas comunes
            $table->index(['service_type', 'is_active']);
            $table->index(['provider_id', 'service_type']);
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
