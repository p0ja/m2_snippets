<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Api\Data;

/**
 * Order ERP status data contract.
 *
 * It is also the Web API request item: every JSON key needs a getter and a setter here, so a client can send
 * increment_id, erp_order_id, status and description, but not id or created_at, which only have getters.
 *
 * @api
 */
interface OrderErpStatusInterface
{
    public const ID = 'id';
    public const INCREMENT_ID = 'increment_id';
    public const ERP_ORDER_ID = 'erp_order_id';
    public const STATUS = 'status';
    public const DESCRIPTION = 'description';
    public const CREATED_AT = 'created_at';

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get order increment id
     *
     * @return string|null
     */
    public function getIncrementId(): ?string;

    /**
     * Set order increment id
     *
     * @param string $incrementId
     * @return $this
     */
    public function setIncrementId(string $incrementId): self;

    /**
     * Get ERP order id
     *
     * @return string|null
     */
    public function getErpOrderId(): ?string;

    /**
     * Set ERP order id
     *
     * @param string $erpOrderId
     * @return $this
     */
    public function setErpOrderId(string $erpOrderId): self;

    /**
     * Get status, one of the \Vendor\OrderSynchronizer\Model\SynchronizationStatus values
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self;

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Set description
     *
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self;

    /**
     * Get creation time, set by the database
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;
}
