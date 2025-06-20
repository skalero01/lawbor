<?php

namespace App\Models;

use App\Enums\AiPromptType;
use App\Enums\AiServiceType;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiServiceConfiguration extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'service_parameters' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'timeout_seconds' => 'integer',
        'max_chars_per_batch' => 'integer',
        'temperature' => 'float',
        'max_tokens' => 'integer',
        'service_type' => AiServiceType::class,
    ];

    protected $attributes = [
        'timeout_seconds' => 1800,
        'max_chars_per_batch' => 6000,
        'temperature' => 0.0,
        'max_tokens' => 4000,
        'is_active' => true,
        'is_default' => false,
        'service_parameters' => "[]",
    ];

    /*
     * Relationships
     */

    public function provider(): BelongsTo
    {
        return $this->belongsTo(AiProvider::class, 'provider_id');
    }

    /*
     * Scopes
     */
    
    #[Scope]
    protected function default(Builder $query, string|AiServiceType $serviceType, ?string $providerName = null): void
    {
        $query->where('service_type', $serviceType)
              ->where('is_active', true)
              ->where('is_default', true)
              ->when($providerName, function (Builder $q, string $name) {
                  return $q->whereHas('provider', function (Builder $subQ) use ($name) {
                      $subQ->where('name', $name);
                  });
              });
    }
    
    /*
     * Methods
     */

    /**
     * Get prompt for a specific prompt type
     * 
     * @param AiPromptType $promptType
     * @return AiPrompt|null
     */
    public function getPrompt(AiPromptType $promptType): ?AiPrompt
    {
        $specificPromptKey = "{$promptType->value}_prompt_id";
        
        if (isset($this->service_parameters[$specificPromptKey])) {
            $promptId = $this->service_parameters[$specificPromptKey];
            return AiPrompt::find($promptId);
        }
        
        if (isset($this->service_parameters['prompt_id'])) {
            $promptId = $this->service_parameters['prompt_id'];
            return AiPrompt::find($promptId);
        }
        
        return AiPrompt::getDefault($this->service_type, $promptType);
    }
}
