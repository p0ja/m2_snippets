<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Model;

use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterface;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterfaceFactory;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusSearchResultsInterface;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusSearchResultsInterfaceFactory;
use Vendor\OrderSynchronizer\Api\OrderErpStatusRepositoryInterface;
use Vendor\OrderSynchronizer\Model\ResourceModel\OrderErpStatus as OrderErpStatusResource;
use Vendor\OrderSynchronizer\Model\ResourceModel\OrderErpStatus\CollectionFactory;

class OrderErpStatusRepository implements OrderErpStatusRepositoryInterface
{
    /**
     * Constructor
     *
     * @param OrderErpStatusInterfaceFactory $orderErpStatusFactory
     * @param OrderErpStatusResource $orderErpStatusResource
     * @param CollectionFactory $collectionFactory
     * @param OrderErpStatusSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        private readonly OrderErpStatusInterfaceFactory $orderErpStatusFactory,
        private readonly OrderErpStatusResource $orderErpStatusResource,
        private readonly CollectionFactory $collectionFactory,
        private readonly OrderErpStatusSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(OrderErpStatusInterface $orderErpStatus): OrderErpStatusInterface
    {
        try {
            $this->orderErpStatusResource->save($orderErpStatus);
        } catch (Exception $e) {
            // The database error stays in the previous exception, for the log; the API client gets a generic message.
            throw new CouldNotSaveException(__('Could not save the ERP status.'), $e);
        }

        return $orderErpStatus;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): OrderErpStatusInterface
    {
        $orderErpStatus = $this->orderErpStatusFactory->create();
        $this->orderErpStatusResource->load($orderErpStatus, $id);

        if (!$orderErpStatus->getId()) {
            throw new NoSuchEntityException(__('ERP status with id "%1" does not exist.', $id));
        }

        return $orderErpStatus;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria): OrderErpStatusSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
