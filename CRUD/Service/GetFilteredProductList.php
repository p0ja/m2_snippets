<?php

declare(strict_types=1);

// Changed: the namespace said "Services" while the directory is "Service", so the class could not be autoloaded.
namespace M2\CRUD\Service;

use M2\CRUD\Api\Data\ItemInterface;
use M2\CRUD\Api\ItemRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Changed: dependencies are injected instead of being created through ObjectManagerInterface. Direct object
 * manager use hides dependencies, bypasses DI preferences and plugins, and is rejected by the Magento coding
 * standard. SearchCriteriaBuilder replaces the hand-built FilterGroup.
 */
class GetFilteredProductList
{
    /**
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ItemRepositoryInterface $itemRepository
     */
    public function __construct(
        private readonly FilterBuilder $filterBuilder,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly ItemRepositoryInterface $itemRepository,
    ) {
    }

    /**
     * @param string $pattern SQL LIKE pattern for the SKU, the value is bound by the collection
     * @return ItemInterface[]
     */
    public function execute(string $pattern): array
    {
        $filter = $this->filterBuilder
            ->setField('item_sku')
            ->setConditionType('like')
            ->setValue($pattern)
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilters([$filter])
            ->create();

        return $this->itemRepository->getList($searchCriteria)->getItems();
    }
}
