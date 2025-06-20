<?php

namespace App\Enums;
use WeblaborMx\TallUtils\Enums\WithSelectInput;

enum AiServiceType: string
{
    use WithSelectInput;

    case ANONYMIZATION = 'anonymization';
    case ANALYSIS = 'analysis';

    public function label()
    {
        return match ($this) {
            self::ANONYMIZATION => __('Anonimización'),
            self::ANALYSIS => __('Análisis'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isAnonymization(): bool
    {
        return $this === self::ANONYMIZATION;
    }

    public function isAnalysis(): bool
    {
        return $this === self::ANALYSIS;
    }
}
