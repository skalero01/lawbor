<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_texts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('document_id')->constrained()->cascadeOnDelete()
              ->unique();                         // relación 1-a-1
        
            $t->longText('original_text');               // Texto crudo (PII incluida)
            $t->longText('anonymized_text')->nullable(); // Texto anonimizado (se llena en PiiJob)
        
            $t->timestamps();
            $t->softDeletes();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('document_texts');
    }
};