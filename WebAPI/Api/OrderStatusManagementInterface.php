<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterface;

/**
 * REST service: POST /V1/vendor-ordersynchronizer/setOrderStatus (see etc/webapi.xml and README.md).
 *
 * The Web API builds the request objects and the JSON response from these docblocks, so the @param and @return
 * types must be exact. Errors are exceptions: the Web API turns InputException into HTTP 400 with the list of
 * errors and NoSuchEntityException into HTTP 404, instead of HTTP 200 with an "error" string.
 *
 * @api
 */
interface OrderStatusManagementInterface
{
    /**
     * Save ERP synchronization statuses and set them as the current ERP status of their orders.
     *
     * Nothing is saved when an item is invalid or an order does not exist.
     *
     * @param \Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterface[] $statuses
     * @return int Number of saved statuses.
     * @throws InputException
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     */
    public function setOrderStatus(array $statuses): int;
}
