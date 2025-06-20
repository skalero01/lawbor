<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_aliases', function (Blueprint $t) {
            $t->id();
            $t->foreignId('document_id')->constrained()->cascadeOnDelete();
        
            $t->string('key');            // [[ALIAS_abc123]]
            $t->text('value')->encrypted(); // PII real (cast: encrypted:text)
            $t->string('entity_type');    // PERSON, DNI, ADDRESS…
        
            $t->timestamps();
            $t->softDeletes();
        
            $t->unique(['document_id','key']);
            $t->index('entity_type');
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('document_aliases');
    }
};