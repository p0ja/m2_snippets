<?php

declare(strict_types=1);

namespace M2\CRUD\ViewModel;

use M2\CRUD\Api\Data\ItemInterface;
use M2\CRUD\Api\ItemRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Added: replaces M2\CRUD\Block\Main. Presentation logic lives in a view model passed to a generic template block
 * through layout XML (the pattern recommended since Magento 2.2), so no custom block class is needed.
 */
class ItemList implements ArgumentInterface
{
    private const ITEMS_LIMIT = 20;

    /**
     * Constructor
     *
     * @param ItemRepositoryInterface $itemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        private readonly ItemRepositoryInterface $itemRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * Items for the template, limited so the page never loads the whole table.
     *
     * @return ItemInterface[]
     */
    public function getItems(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->setPageSize(self::ITEMS_LIMIT)
            ->setCurrentPage(1)
            ->create();

        return $this->itemRepository->getList($searchCriteria)->getItems();
    }
}
