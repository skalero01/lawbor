<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_analysis', function (Blueprint $table) {
            $table->id(); // id bigint auto increment
            $table->unsignedBigInteger('document_id')->comment('ID del documento analizado');
            $table->text('content')->comment('Contenido del documento');
            $table->json('payload')->nullable()->comment('Resultado estructurado del análisis');
            $table->json('processing_metadata')->nullable()->comment('Metadatos adicionales del procesamiento');
            $table->unsignedBigInteger('ai_service_configuration_id')->nullable()->comment('Configuración del servicio de IA usado');
            $table->unsignedBigInteger('ai_prompt_id')->nullable()->comment('Prompt de IA utilizado');
            $table->timestamps(); // created_at y updated_at
            $table->softDeletes(); // deleted_at
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('document_analysis');
    }
};