<?php

declare(strict_types=1);

namespace Vendor\NewtypesGraphQl\Api\Data;

/**
 * Newtype data contract.
 *
 * Changed: the getters and setters were only @method annotations on an empty interface, so nothing enforced them
 * and implementations could omit them. They are declared methods now, with the field names as constants and the
 * cache tag used by the GraphQL cache identity.
 *
 * @api
 */
interface NewtypeInterface
{
    public const CACHE_TAG = 'vendor_newtype';

    public const ID = 'id';
    public const NAME = 'name';
    public const STORE_ID = 'store_id';
    public const CATEGORY_ID = 'category_id';
    public const CONTENT = 'content';
    public const URL = 'url';
    public const DATE_FROM = 'date_from';
    public const DATE_TO = 'date_to';
    public const PRIORITY = 'priority';
    public const ACTIVE = 'active';

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * Get store id
     *
     * @return int|null
     */
    public function getStoreId(): ?int;

    /**
     * Set store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): self;

    /**
     * Get category id
     *
     * @return int|null
     */
    public function getCategoryId(): ?int;

    /**
     * Set category id
     *
     * @param int $categoryId
     * @return $this
     */
    public function setCategoryId(int $categoryId): self;

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Set content
     *
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self;

    /**
     * Get url
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * Set url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): self;

    /**
     * Get date from
     *
     * @return string|null
     */
    public function getDateFrom(): ?string;

    /**
     * Set date from
     *
     * @param string $dateFrom
     * @return $this
     */
    public function setDateFrom(string $dateFrom): self;

    /**
     * Get date to
     *
     * @return string|null
     */
    public function getDateTo(): ?string;

    /**
     * Set date to
     *
     * @param string $dateTo
     * @return $this
     */
    public function setDateTo(string $dateTo): self;

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority(): int;

    /**
     * Set priority
     *
     * @param int $priority
     * @return $this
     */
    public function setPriority(int $priority): self;

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Set active
     *
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active): self;
}
