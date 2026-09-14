<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Block\Adminhtml\Order\View\Tab;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Sales\Api\OrderRepositoryInterface;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterface;
use Vendor\OrderSynchronizer\Api\OrderErpStatusRepositoryInterface;
use Vendor\OrderSynchronizer\Model\SynchronizationStatus;

/**
 * "ERP Status History" tab on the admin order view, added in view/adminhtml/layout/sales_order_view.xml.
 *
 * The order comes from the order_id request parameter and the repository instead of the deprecated Registry,
 * and the history is read through the ERP status repository.
 */
class ErpStatusHistory extends Template implements TabInterface
{
    /**
     * @var string
     */
    protected $_template = 'Vendor_OrderSynchronizer::order/view/tab/erp_status_history.phtml';

    /**
     * Constructor
     *
     * @param Context $context
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderErpStatusRepositoryInterface $orderErpStatusRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderErpStatusRepositoryInterface $orderErpStatusRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly SortOrderBuilder $sortOrderBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Statuses of the current order, newest first
     *
     * @return OrderErpStatusInterface[]
     */
    public function getHistory(): array
    {
        try {
            $order = $this->orderRepository->get((int)$this->getRequest()->getParam('order_id'));
        } catch (NoSuchEntityException $e) {
            return [];
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(OrderErpStatusInterface::INCREMENT_ID, $order->getIncrementId())
            ->addSortOrder(
                $this->sortOrderBuilder->setField(OrderErpStatusInterface::ID)->setDescendingDirection()->create()
            )
            ->create();

        return $this->orderErpStatusRepository->getList($searchCriteria)->getItems();
    }

    /**
     * Translated status label, the raw value for unknown statuses
     *
     * @param OrderErpStatusInterface $status
     * @return string
     */
    public function getStatusLabel(OrderErpStatusInterface $status): string
    {
        $value = (string)$status->getStatus();

        return (string)(SynchronizationStatus::tryFrom($value)?->getLabel() ?? $value);
    }

    /**
     * @inheritDoc
     */
    public function getTabLabel(): Phrase
    {
        return __('ERP Status History');
    }

    /**
     * @inheritDoc
     */
    public function getTabTitle(): Phrase
    {
        return __('ERP Status History');
    }

    /**
     * @inheritDoc
     */
    public function canShowTab(): bool
    {
        return $this->_authorization->isAllowed('Vendor_OrderSynchronizer::erp_status');
    }

    /**
     * @inheritDoc
     */
    public function isHidden(): bool
    {
        return false;
    }
}
