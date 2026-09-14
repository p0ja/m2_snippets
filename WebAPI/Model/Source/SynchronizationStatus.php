<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Vendor\OrderSynchronizer\Model\SynchronizationStatus as Status;

/**
 * Options of the ERP status column filter in the sales order grid.
 *
 * The options come from the enum: reading DISTINCT values from the grid table ran a query on every grid load
 * and missed the statuses no order has yet.
 */
class SynchronizationStatus implements OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        return array_map(
            static fn (Status $status): array => ['value' => $status->value, 'label' => $status->getLabel()],
            Status::cases()
        );
    }
}
