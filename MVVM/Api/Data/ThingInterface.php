<?php

declare(strict_types=1);

namespace M2\MVVM\Api\Data;

/**
 * Data interfaces are used to maintain the integrity of the data: they define all the getters and setters of an
 * entity, so code keeps getting consistent data even when the model or the business logic changes.
 *
 * Changed: the interface was empty and did not define any of that; it now declares the fields as constants with
 * their getters and setters.
 *
 * @api
 */
interface ThingInterface
{
    public const THING_ID = 'thing_id';
    public const TITLE = 'title';
    public const IS_ACTIVE = 'is_active';
    public const CREATION_TIME = 'creation_time';
    public const UPDATE_TIME = 'update_time';

    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self;

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Set is active
     *
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive): self;

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime(): ?string;

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime(): ?string;
}
