<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use WeblaborMx\TallUtils\Models\WithActivityLog;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class DocumentAnalysis extends Model
{
	use SearchableTrait, SoftDeletes, WithActivityLog;
	
    protected $table = 'document_analysis'; 
    protected $guarded = [];
    protected $searchable = [
        'columns' => [
            'name' => 10,
        ],
    ];
    
    protected $casts = [
        'payload' => 'array',
        'processing_metadata' => 'array',
    ];

    /*
     * Relationships
     */

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
    
    public function aiServiceConfiguration(): BelongsTo
    {
        return $this->belongsTo(AiServiceConfiguration::class);
    }
    
    public function aiPrompt(): BelongsTo
    {
        return $this->belongsTo(AiPrompt::class);
    }
}
