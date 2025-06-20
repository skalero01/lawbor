<?php

namespace App\Enums;

use WeblaborMx\TallUtils\Enums\WithSelectInput;

enum Status: string
{
    use WithSelectInput;

    case Pending = 'pending';
    case Queued = 'queued';
    case Processing = 'processing';
    case Completed = 'completed';
    case Error = 'error';

    public function label()
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::Queued => __('Queued'),
            self::Processing => __('Processing'),
            self::Completed => __('Completed'),
            self::Error => __('Error'),
        };
    }

    public function checkColor()
    {
        return match ($this) {
            self::Pending => 'bg-gray-400',
            self::Queued => 'bg-blue-400',
            self::Processing => 'bg-yellow-400',
            self::Completed => 'bg-green-400',
            self::Error => 'bg-red-400',
        };
    }

    public function classBadge()
    {
        return match ($this) {
            self::Pending => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
            self::Queued => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            self::Processing => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            self::Completed => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            self::Error => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        };
    }

    public function isPending()
    {
        return $this === self::Pending;
    }

    public function isQueued()
    {
        return $this === self::Queued;
    }

    public function isProcessing()
    {
        return $this === self::Processing;
    }

    public function isCompleted()
    {
        return $this === self::Completed;
    }

    public function isError()
    {
        return $this === self::Error;
    }       
}