<?php

namespace App\Models;

use App\Enums\AiServiceType;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiProvider extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'available_models' => 'array',
        'default_parameters' => 'array',
        'is_active' => 'boolean',
        'api_key' => 'encrypted',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    /*
     * Relationships
     */
    
    public function configurations(): HasMany
    {
        return $this->hasMany(AiServiceConfiguration::class, 'provider_id');
    }
}
