<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterface;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusSearchResultsInterface;

/**
 * Order ERP status repository service contract.
 *
 * @api
 */
interface OrderErpStatusRepositoryInterface
{
    /**
     * Save a status.
     *
     * @param OrderErpStatusInterface $orderErpStatus
     * @return OrderErpStatusInterface
     * @throws CouldNotSaveException
     */
    public function save(OrderErpStatusInterface $orderErpStatus): OrderErpStatusInterface;

    /**
     * Get a status by id.
     *
     * @param int $id
     * @return OrderErpStatusInterface
     * @throws NoSuchEntityException If the status does not exist.
     */
    public function getById(int $id): OrderErpStatusInterface;

    /**
     * Retrieve the statuses matching the criteria.
     *
     * @param SearchCriteriaInterface $criteria
     * @return OrderErpStatusSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): OrderErpStatusSearchResultsInterface;
}
