<?php

declare(strict_types=1);

namespace M2\MVVM\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Added: typed search results for things; etc/di.xml maps it to Magento\Framework\Api\SearchResults.
 *
 * @api
 */
interface ThingSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return ThingInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param ThingInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
