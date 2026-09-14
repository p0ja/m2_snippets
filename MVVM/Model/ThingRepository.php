<?php

declare(strict_types=1);

namespace M2\MVVM\Model;

use Exception;
use M2\MVVM\Api\Data\ThingInterface;
use M2\MVVM\Api\ThingRepositoryInterface;
use M2\MVVM\Model\ResourceModel\Thing as ObjectResourceModel;
use M2\MVVM\Model\ResourceModel\Thing\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ThingRepository implements ThingRepositoryInterface
{
    /**
     * @param ThingFactory $objectFactory
     * @param ObjectResourceModel $objectResourceModel
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        private readonly ThingFactory $objectFactory,
        private readonly ObjectResourceModel $objectResourceModel,
        private readonly CollectionFactory $collectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory
    ) {
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(ThingInterface $object): ThingInterface
    {
        try {
            $this->objectResourceModel->save($object);
        } catch (Exception $e) {
            // Changed: the database error text is no longer shown in the admin message; the original exception
            // is passed on for the log. Concatenating the Phrase also produced a string the constructor rejects.
            throw new CouldNotSaveException(__('Error saving object'), $e);
        }

        return $object;
    }

    public function deleteById($id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(ThingInterface $object): bool
    {
        try {
            $this->objectResourceModel->delete($object);
        } catch (Exception $e) {
            // Changed: see save(); the database error text stays in the previous exception.
            throw new CouldNotDeleteException(__('Error removing object'), $e);
        }

        return true;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getById($id): ThingInterface
    {
        $object = $this->objectFactory->create();
        $this->objectResourceModel->load($object, $id);

        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }

        return $object;
    }

    /**
     * @returns [] ThingInterface
     */
    public function getList(SearchCriteriaInterface $criteria): array
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $collection = $this->collectionFactory->create();

        $this->addFilterCriteria($criteria, $collection);
        $searchResults->setTotalCount($collection->getSize());
        $this->addSortOrdersCriteria($criteria, $collection);

        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];

        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }

        $searchResults->setItems($objects);

        return $searchResults;
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @param $collection
     * @return void
     */
    private function addFilterCriteria(SearchCriteriaInterface $criteria, $collection): void
    {
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];

            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }

            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @param $collection
     * @return void
     */
    private function addSortOrdersCriteria(SearchCriteriaInterface $criteria, $collection): void
    {
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
    }
}
