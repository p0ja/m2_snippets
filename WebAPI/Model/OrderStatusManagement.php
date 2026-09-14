<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterface;
use Vendor\OrderSynchronizer\Api\OrderErpStatusRepositoryInterface;
use Vendor\OrderSynchronizer\Api\OrderStatusManagementInterface;

/**
 * Orders are read and saved through OrderRepositoryInterface instead of the deprecated loadByIncrementId() and
 * $order->save(); the repository save also refreshes the order's sales_order_grid row.
 */
class OrderStatusManagement implements OrderStatusManagementInterface
{
    public const ORDER_COLUMN = 'current_erp_status';

    /**
     * Constructor
     *
     * @param OrderErpStatusValidator $validator
     * @param OrderErpStatusRepositoryInterface $orderErpStatusRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly OrderErpStatusValidator $validator,
        private readonly OrderErpStatusRepositoryInterface $orderErpStatusRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function setOrderStatus(array $statuses): int
    {
        $errors = $this->validator->validate($statuses);
        if ($errors !== []) {
            $exception = new InputException();
            foreach ($errors as $error) {
                $exception->addError($error);
            }

            throw $exception;
        }

        // Every order is looked up before anything is saved, so a wrong increment id rejects the whole request.
        $orders = $this->getOrdersByIncrementId(
            array_map(static fn (OrderErpStatusInterface $status) => $status->getIncrementId(), $statuses)
        );

        foreach ($statuses as $status) {
            if (!isset($orders[$status->getIncrementId()])) {
                throw new NoSuchEntityException(__('Order "%1" does not exist.', $status->getIncrementId()));
            }
        }

        foreach ($statuses as $status) {
            $order = $orders[$status->getIncrementId()];

            $this->orderErpStatusRepository->save($status);
            $order->setData(self::ORDER_COLUMN, $status->getStatus());
            $this->orderRepository->save($order);

            $this->logger->info(
                sprintf('ERP status "%s" set for order %s', $status->getStatus(), $status->getIncrementId())
            );
        }

        return count($statuses);
    }

    /**
     * Load orders with one query
     *
     * @param string[] $incrementIds
     * @return OrderInterface[] Keyed by increment id.
     */
    private function getOrdersByIncrementId(array $incrementIds): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(OrderInterface::INCREMENT_ID, array_unique($incrementIds), 'in')
            ->create();

        $orders = [];
        foreach ($this->orderRepository->getList($searchCriteria)->getItems() as $order) {
            $orders[$order->getIncrementId()] = $order;
        }

        return $orders;
    }
}
