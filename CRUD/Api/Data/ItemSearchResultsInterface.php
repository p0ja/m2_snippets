<?php

declare(strict_types=1);

namespace M2\CRUD\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Added: typed search results for items. The repository returned the generic SearchResultsInterface, so callers
 * (and the REST/SOAP schema generated from service contracts) did not know which objects getItems() returns.
 * etc/di.xml maps it to Magento\Framework\Api\SearchResults.
 *
 * @api
 */
interface ItemSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return ItemInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param ItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
