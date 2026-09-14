<?php

declare(strict_types=1);

namespace M2\MVVM\Model;

use Exception;
use M2\MVVM\Api\Data\ThingInterface;
use M2\MVVM\Api\Data\ThingSearchResultsInterface;
use M2\MVVM\Api\Data\ThingSearchResultsInterfaceFactory;
use M2\MVVM\Api\ThingRepositoryInterface;
use M2\MVVM\Model\ResourceModel\Thing as ThingResource;
use M2\MVVM\Model\ResourceModel\Thing\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Changed:
 * - getList() returns ThingSearchResultsInterface (the old array return type was a TypeError) and applies the
 *   criteria with CollectionProcessorInterface; the removed filter/sort helpers referenced SortOrder without
 *   importing it;
 * - getById() and deleteById() take int ids, as the interface declares.
 */
class ThingRepository implements ThingRepositoryInterface
{
    /**
     * Constructor
     *
     * @param ThingFactory $thingFactory
     * @param ThingResource $thingResource
     * @param CollectionFactory $collectionFactory
     * @param ThingSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        private readonly ThingFactory $thingFactory,
        private readonly ThingResource $thingResource,
        private readonly CollectionFactory $collectionFactory,
        private readonly ThingSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(ThingInterface $thing): ThingInterface
    {
        try {
            $this->thingResource->save($thing);
        } catch (Exception $e) {
            // The database error text is not shown in the admin message; the original exception is kept for the log.
            throw new CouldNotSaveException(__('Error saving object'), $e);
        }

        return $thing;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ThingInterface
    {
        $thing = $this->thingFactory->create();
        $this->thingResource->load($thing, $id);

        if (!$thing->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }

        return $thing;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria): ThingSearchResultsInterface
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
    public function delete(ThingInterface $thing): bool
    {
        try {
            $this->thingResource->delete($thing);
        } catch (Exception $e) {
            // See save(); the database error text stays in the previous exception.
            throw new CouldNotDeleteException(__('Error removing object'), $e);
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
