<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Typed search results for order ERP statuses, mapped to Magento\Framework\Api\SearchResults in etc/di.xml.
 *
 * @api
 */
interface OrderErpStatusSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return OrderErpStatusInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param OrderErpStatusInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
