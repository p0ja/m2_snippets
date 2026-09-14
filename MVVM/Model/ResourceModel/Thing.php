<?php

declare(strict_types=1);

namespace M2\MVVM\Model\ResourceModel;

use M2\MVVM\Api\Data\ThingInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Thing extends AbstractDb
{
    public const TABLE_NAME = 'm2_mvvm_thing';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        // Changed: the table name is defined once; the removed InstallSchema created "M2_mvvm_thing", a different
        // table on Linux MySQL. etc/db_schema.xml migrates it.

        $this->_init(self::TABLE_NAME, ThingInterface::THING_ID);
    }
}
