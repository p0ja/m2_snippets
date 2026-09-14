<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Model;

use Magento\Framework\Phrase;

/**
 * Allowed ERP synchronization statuses.
 *
 * A backed enum replaces the class of constants read with constant("self::$key"): tryFrom() validates a value
 * and cases() lists them for the grid filter.
 */
enum SynchronizationStatus: string
{
    case New = 'new';
    case Waiting = 'waiting';
    case Accepted = 'accepted';
    case Synchronized = 'synchronized';
    case Rejected = 'rejected';
    case Error = 'error';

    /**
     * Translated label
     *
     * @return Phrase
     */
    public function getLabel(): Phrase
    {
        return match ($this) {
            self::New => __('New'),
            self::Waiting => __('Waiting'),
            self::Accepted => __('Accepted'),
            self::Synchronized => __('Synchronized'),
            self::Rejected => __('Rejected'),
            self::Error => __('Error'),
        };
    }
}
