<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterface;
use Vendor\OrderSynchronizer\Model\ResourceModel\OrderErpStatus as OrderErpStatusResource;

class OrderErpStatus extends AbstractModel implements OrderErpStatusInterface, IdentityInterface
{
    public const CACHE_TAG = 'vendor_order_erp_status';

    /**
     * @var string
     */
    protected $_eventPrefix = 'vendor_order_erp_status';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(OrderErpStatusResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritDoc
     */
    public function getIncrementId(): ?string
    {
        return $this->getData(self::INCREMENT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setIncrementId(string $incrementId): OrderErpStatusInterface
    {
        return $this->setData(self::INCREMENT_ID, $incrementId);
    }

    /**
     * @inheritDoc
     */
    public function getErpOrderId(): ?string
    {
        return $this->getData(self::ERP_ORDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setErpOrderId(string $erpOrderId): OrderErpStatusInterface
    {
        return $this->setData(self::ERP_ORDER_ID, $erpOrderId);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): ?string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): OrderErpStatusInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDescription(?string $description): OrderErpStatusInterface
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }
}
