<?php

declare(strict_types=1);

namespace Vendor\DynamicRows\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Changed: implements OptionSourceInterface; Magento\Framework\Option\ArrayInterface is deprecated. The labels are
 * translatable phrases.
 */
class Condition implements OptionSourceInterface
{
    public const GREATER_THAN = 'gt';
    public const EQUAL = 'eq';

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        return [
           ['label' => __('GreaterThan'), 'value' => self::GREATER_THAN],
           ['label' => __('Equal'), 'value' => self::EQUAL],
        ];
    }
}
