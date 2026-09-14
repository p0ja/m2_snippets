<?php

declare(strict_types=1);

namespace M2\CRUD\Model;

use M2\CRUD\Api\Data\ItemInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Changed: implements the getters/setters of ItemInterface on top of the magic data array, and the cache tag is
 * lowercase like the table name (see etc/db_schema.xml).
 */
class Item extends AbstractModel implements ItemInterface, IdentityInterface
{
    public const CACHE_TAG = 'm2_crud_item';

    /**
     * @var string
     */
    protected $_eventPrefix = 'm2_crud_item';

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
    public function getSku(): ?string
    {
        return $this->getData(self::SKU);
    }

    /**
     * @inheritDoc
     */
    public function setSku(string $sku): ItemInterface
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setTitle(string $title): ItemInterface
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function getDateCompleted(): ?string
    {
        return $this->getData(self::DATE_COMPLETED);
    }

    /**
     * @inheritDoc
     */
    public function setDateCompleted(?string $dateCompleted): ItemInterface
    {
        return $this->setData(self::DATE_COMPLETED, $dateCompleted);
    }

    /**
     * @inheritDoc
     */
    public function getCreationTime(): ?string
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * @inheritDoc
     */
    public function getUpdateTime(): ?string
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive(bool $isActive): ItemInterface
    {
        return $this->setData(self::IS_ACTIVE, (int)$isActive);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Item::class);
    }
}
