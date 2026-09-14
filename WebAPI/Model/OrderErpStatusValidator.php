<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Model;

use Magento\Framework\Phrase;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterface;

/**
 * Validates the Web API request items.
 *
 * It returns every error at once instead of stopping at the first one, so the ERP can fix the whole request, and
 * only checks the data itself; whether the orders exist is checked by OrderStatusManagement.
 */
class OrderErpStatusValidator
{
    /**
     * The varchar length of the columns in etc/db_schema.xml
     */
    private const MAX_LENGTH = 32;

    /**
     * Validate the items
     *
     * @param OrderErpStatusInterface[] $statuses
     * @return Phrase[] Empty when the items are valid.
     */
    public function validate(array $statuses): array
    {
        if ($statuses === []) {
            return [__('At least one status is required.')];
        }

        $errors = [];
        foreach (array_values($statuses) as $index => $status) {
            $values = [
                OrderErpStatusInterface::INCREMENT_ID => $status->getIncrementId(),
                OrderErpStatusInterface::ERP_ORDER_ID => $status->getErpOrderId(),
                OrderErpStatusInterface::STATUS => $status->getStatus(),
            ];

            foreach ($values as $field => $value) {
                if (trim((string)$value) === '') {
                    $errors[] = __('Item %1: "%2" is required.', $index, $field);
                } elseif (strlen((string)$value) > self::MAX_LENGTH) {
                    $errors[] = __('Item %1: "%2" is longer than %3 characters.', $index, $field, self::MAX_LENGTH);
                }
            }

            $statusValue = (string)$status->getStatus();
            if ($statusValue !== '' && SynchronizationStatus::tryFrom($statusValue) === null) {
                $errors[] = __(
                    'Item %1: status "%2" is not one of: %3.',
                    $index,
                    $statusValue,
                    implode(', ', array_column(SynchronizationStatus::cases(), 'value'))
                );
            }
        }

        return $errors;
    }
}
