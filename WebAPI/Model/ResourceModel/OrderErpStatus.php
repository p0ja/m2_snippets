<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterface;

class OrderErpStatus extends AbstractDb
{
    public const TABLE_NAME = 'vendor_order_erp_status';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, OrderErpStatusInterface::ID);
    }
}
