<?php

declare(strict_types=1);

namespace M2\CRUD\Model\ResourceModel;

use M2\CRUD\Api\Data\ItemInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Item extends AbstractDb
{
    public const TABLE_NAME = 'm2_crud_item';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        // Changed: the table name is lowercase and defined once; it was "M2_crud_item", which on Linux MySQL is a
        // different table than the lowercase name used elsewhere. etc/db_schema.xml migrates the old table.

        $this->_init(self::TABLE_NAME, ItemInterface::ITEM_ID);
    }
}
