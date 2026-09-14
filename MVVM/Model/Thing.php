<?php

declare(strict_types=1);

namespace M2\MVVM\Model;

use M2\MVVM\Api\Data\ThingInterface;
use M2\MVVM\Model\ResourceModel\Thing as ResourceThing;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Changed: implements the getters and setters of ThingInterface; the status constants moved to the interface
 * and stay available here through inheritance.
 */
class Thing extends AbstractModel implements ThingInterface, IdentityInterface
{
    public const CACHE_TAG = 'm2_mvvm_thing';

    /**
     * @var string
     */
    protected $_eventPrefix = 'm2_mvvm_thing';

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
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setTitle(string $title): ThingInterface
    {
        return $this->setData(self::TITLE, $title);
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
    public function setIsActive(bool $isActive): ThingInterface
    {
        return $this->setData(self::IS_ACTIVE, $isActive ? self::STATUS_ENABLED : self::STATUS_DISABLED);
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
    protected function _construct()
    {
        $this->_init(ResourceThing::class);
    }
}
