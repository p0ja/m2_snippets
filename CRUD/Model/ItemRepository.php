<?php

namespace M2\CRUD\Model;

use Exception;
use M2\CRUD\Api\Data\ItemInterface;
use M2\CRUD\Api\ItemRepositoryInterface;
use M2\CRUD\Model\ResourceModel\Item as ObjectResourceModel;
use M2\CRUD\Model\ResourceModel\Item\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ItemRepository implements ItemRepositoryInterface
{
    /**
     * @param ItemFactory $objectFactory
     * @param ObjectResourceModel $objectResourceModel
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        private readonly ItemFactory $objectFactory,
        private readonly ObjectResourceModel $objectResourceModel,
        private readonly CollectionFactory $collectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(ItemInterface $item): ItemInterface
    {
        try {
            $this->objectResourceModel->save($item);
        } catch (Exception $e) {
            // Changed: the original exception is passed as the previous exception (the second argument must be a
            // Throwable, a string was a TypeError), so the database error is logged but not shown to the user.
            throw new CouldNotSaveException(__('Could not save item'), $e);
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @inheritDoc
     */
    public function delete(ItemInterface $item): bool
    {
        try {
            $this->objectResourceModel->delete($item);
        } catch (Exception $e) {
            // Changed: the database error message is no longer appended to the message shown to the user; it is
            // kept as the previous exception for the log. Concatenating the Phrase also turned it into a string,
            // which the exception constructor rejects.
            throw new CouldNotDeleteException(__('Could not delete item.'), $e);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ItemInterface
    {
        $item = $this->objectFactory->create();
        $this->objectResourceModel->load($item, $id);
        if (!$item->getId()) {
            throw new NoSuchEntityException(__('Item with id "%1" does not exist.', $id));
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria): SearchResultsInterface
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $collection = $this->collectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() === SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];

        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }
        $searchResults->setItems($objects);

        return $searchResults;
    }
}
