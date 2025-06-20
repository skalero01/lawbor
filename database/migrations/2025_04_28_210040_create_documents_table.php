<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('documents', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        
            $t->string('name');          // Nombre del archivo
            $t->string('path');              
        
            $t->integer('size');
            $t->text('error')->nullable();        // Mensaje si falló algún job

            // Control de estado de cada proceso
            $t->string('status_ocr')->default('pending');            // pending | queued | processing | completed | error
            $t->string('status_anonymization')->default('pending');  // pending | queued | processing | completed | error
            $t->string('status_analysis')->default('pending');       // pending | queued | processing | completed | error

            // Timestamps de cada proceso
            $t->timestamp('ocr_completed_at')->nullable(); 
            $t->timestamp('anonymization_completed_at')->nullable(); 
            $t->timestamp('analysis_completed_at')->nullable(); 
        
            $t->timestamps();
            $t->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
