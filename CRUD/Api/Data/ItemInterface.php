<?php

declare(strict_types=1);

namespace M2\CRUD\Api\Data;

/**
 * Item data contract.
 *
 * Changed: the interface was empty, so it described nothing and code had to fall back to getData('item_sku').
 * A data interface declares the entity fields as constants and their getters/setters; the model implements
 * them and other code depends only on this interface.
 *
 * @api
 */
interface ItemInterface
{
    public const ITEM_ID = 'item_id';
    public const SKU = 'item_sku';
    public const TITLE = 'item_title';
    public const DATE_COMPLETED = 'date_completed';
    public const CREATION_TIME = 'creation_time';
    public const UPDATE_TIME = 'update_time';
    public const IS_ACTIVE = 'is_active';

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
     * Get sku
     *
     * @return string|null
     */
    public function getSku(): ?string;

    /**
     * Set sku
     *
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): self;

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
     * Get date completed
     *
     * @return string|null
     */
    public function getDateCompleted(): ?string;

    /**
     * Set date completed
     *
     * @param string|null $dateCompleted
     * @return $this
     */
    public function setDateCompleted(?string $dateCompleted): self;

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
}
