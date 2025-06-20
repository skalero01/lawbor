<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use WeblaborMx\TallUtils\Models\WithActivityLog;

class DocumentText extends Model
{
	use SearchableTrait,SoftDeletes, WithActivityLog;
	
    protected $guarded = [];
    protected $searchable = [
        'columns' => [
            'name' => 10,
        ],
    ];

    /*
     * Relationships
     */

     public function document()
     {
         return $this->belongsTo(Document::class);
     }
}
