<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use WeblaborMx\TallUtils\Models\WithActivityLog;
use App\Models\User;
use App\Models\DocumentText;
use App\Models\DocumentAlias;
use App\Models\DocumentAnalysis;
use App\Enums\Status;
use App\Observers\DocumentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([DocumentObserver::class])]
class Document extends Model
{
	use SearchableTrait, SoftDeletes, WithActivityLog;
	
    protected $guarded = [];

    protected $casts = [
        'status_ocr' => Status::class,
        'status_anonymization' => Status::class,
        'status_analysis' => Status::class,
        'ocr_completed_at' => 'datetime',
        'anonymization_completed_at' => 'datetime',
        'analysis_completed_at' => 'datetime',
    ];

    protected $searchable = [
        'columns' => [
            'name' => 10,
        ],
    ];

    /*
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function text()            
    {
        return $this->hasOne(DocumentText::class);
    }

    public function aliases()         
    {
        return $this->hasMany(DocumentAlias::class);
    }

    public function analysis()        
    {
        return $this->hasOne(DocumentAnalysis::class);
    }
}
