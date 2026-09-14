<?php

declare(strict_types=1);

namespace M2\CRUD\Model;

use Exception;
use M2\CRUD\Api\Data\ItemInterface;
use M2\CRUD\Api\Data\ItemSearchResultsInterface;
use M2\CRUD\Api\Data\ItemSearchResultsInterfaceFactory;
use M2\CRUD\Api\ItemRepositoryInterface;
use M2\CRUD\Model\ResourceModel\Item as ItemResource;
use M2\CRUD\Model\ResourceModel\Item\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Changed:
 * - getList() hands the search criteria to CollectionProcessorInterface, the core way to apply filters, sort
 *   orders and paging; the hand-written loops referenced SortOrder without importing it (fatal with sorting);
 * - getList() returns the typed ItemSearchResultsInterface;
 * - the model comes from ItemFactory and is typed as ItemInterface.
 */
class ItemRepository implements ItemRepositoryInterface
{
    /**
     * Constructor
     *
     * @param ItemFactory $itemFactory
     * @param ItemResource $itemResource
     * @param CollectionFactory $collectionFactory
     * @param ItemSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        private readonly ItemFactory $itemFactory,
        private readonly ItemResource $itemResource,
        private readonly CollectionFactory $collectionFactory,
        private readonly ItemSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(ItemInterface $item): ItemInterface
    {
        try {
            $this->itemResource->save($item);
        } catch (Exception $e) {
            // The original exception is passed on for the log; the database error is not shown to the user.
            throw new CouldNotSaveException(__('Could not save item'), $e);
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ItemInterface
    {
        $item = $this->itemFactory->create();
        $this->itemResource->load($item, $id);

        if (!$item->getId()) {
            throw new NoSuchEntityException(__('Item with id "%1" does not exist.', $id));
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria): ItemSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(ItemInterface $item): bool
    {
        try {
            $this->itemResource->delete($item);
        } catch (Exception $e) {
            // The database error text stays in the previous exception, for the log.
            throw new CouldNotDeleteException(__('Could not delete item.'), $e);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }
}
