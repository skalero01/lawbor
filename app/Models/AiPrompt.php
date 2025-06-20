<?php

namespace App\Models;

use App\Enums\AiPromptType;
use App\Enums\AiServiceType;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiPrompt extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'analysis_fields' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'service_type' => AiServiceType::class,
        'prompt_type' => AiPromptType::class,
    ];

    protected $attributes = [
        'is_active' => true,
        'is_default' => false,
    ];

    /**
     * Scopes
     */

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    #[Scope]
    protected function forServiceType(Builder $query, string|AiServiceType $serviceType): void
    {
        $query->where('service_type', $serviceType);
    }

    #[Scope]
    protected function ofType(Builder $query, string|AiPromptType $promptType): void
    {
        $query->where('prompt_type', $promptType);
    }

    #[Scope]
    protected function default(Builder $query): void
    {
        $query->where('is_default', true);
    }

    /*
     * Methods
     */
    
    /**
     * Get default prompt for a specific service type and prompt type
     *
     * @param string|AiServiceType $serviceType
     * @param string|AiPromptType $promptType
     * @return self|null
     */
    public static function getDefault(string|AiServiceType $serviceType, string|AiPromptType $promptType): ?self
    {
        $query = self::query();
        $query->forServiceType($serviceType)
            ->ofType($promptType)
            ->default()
            ->active();
        return $query->first();
    }


}
