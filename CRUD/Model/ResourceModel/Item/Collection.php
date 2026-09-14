<?php

declare(strict_types=1);

namespace M2\CRUD\Model\ResourceModel\Item;

use M2\CRUD\Model\Item;
use M2\CRUD\Model\ResourceModel\Item as ItemResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(Item::class, ItemResource::class);
    }
}
