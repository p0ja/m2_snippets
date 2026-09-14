<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Model\ResourceModel\OrderErpStatus;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\OrderSynchronizer\Model\OrderErpStatus;
use Vendor\OrderSynchronizer\Model\ResourceModel\OrderErpStatus as OrderErpStatusResource;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(OrderErpStatus::class, OrderErpStatusResource::class);
    }
}
