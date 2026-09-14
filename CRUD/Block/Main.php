<?php

declare(strict_types=1);

namespace M2\CRUD\Block;

use M2\CRUD\Api\ItemRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Changed: this block used to save a new item in _prepareLayout() and var_dump() every stored row, so each
 * anonymous visit of /m2_crud/index/index wrote to the database and printed the whole table into the page.
 * A block only reads; writes belong to POST controllers, CLI commands or data patches. The template now
 * prints a limited, escaped list loaded through the repository.
 */
class Main extends Template
{
    private const ITEMS_LIMIT = 20;

    /**
     * @param Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        private readonly ItemRepositoryInterface $itemRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Items for the template, limited so the page never loads the whole table.
     *
     * @return \M2\CRUD\Api\Data\ItemInterface[]
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
