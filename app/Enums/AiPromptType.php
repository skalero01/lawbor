<?php

namespace App\Enums;
use WeblaborMx\TallUtils\Enums\WithSelectInput;

enum AiPromptType: string
{
    use WithSelectInput;

    case STANDARD = 'standard';
    case CHUNK = 'chunk';
    case COMBINATION = 'combination';

    public function label()
    {
        return match ($this) {
            self::STANDARD => __('Standard'),
            self::CHUNK => __('Chunk'),
            self::COMBINATION => __('Combination'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get prompt types available for a specific service type
     *
     * @param string $serviceType
     * @return array
     */
    public static function forServiceType(string $serviceType): array
    {
        $types = match ($serviceType) {
            AiServiceType::ANONYMIZATION->value => [self::STANDARD],
            AiServiceType::ANALYSIS->value => [self::STANDARD, self::CHUNK, self::COMBINATION],
            default => [],
        };
    
        return collect($types)->mapWithKeys(fn($type) => [$type->value => $type->label()])->toArray();
    }
    

    public function isChunk(): bool
    {
        return $this === self::CHUNK;
    }

    public function isCombination(): bool
    {
        return $this === self::COMBINATION;
    }

    public function isStandard(): bool
    {
        return $this === self::STANDARD;
    }
}
