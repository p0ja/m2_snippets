<?php

declare(strict_types=1);

namespace Vendor\FreeCodes\Service;

use Vendor\FreeCodes\Api\ClaimFreeCodeInterface;
use Vendor\FreeCodes\Model\ResourceModel\FreeCode as FreeCodeResource;
use Vendor\FreeCodes\Model\Session;

/**
 * The session remembers the claimed code, so reloading the page or clicking again returns the same code instead
 * of taking another one from the pool.
 */
class ClaimFreeCode implements ClaimFreeCodeInterface
{
    private const int MAX_TRIES = 5;

    /**
     * Constructor
     *
     * @param FreeCodeResource $freeCodeResource
     * @param Session $session
     */
    public function __construct(
        private readonly FreeCodeResource $freeCodeResource,
        private readonly Session $session
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(): ?string
    {
        $claimedCode = $this->session->getClaimedCode();
        if ($claimedCode !== null) {
            return $claimedCode;
        }

        $code = $this->claimFreeCode();
        if ($code !== null) {
            $this->session->setClaimedCode($code);
        }

        return $code;
    }

    /**
     * Claim a code from the pool
     *
     * Another visitor can claim the found code first; then the next free code is tried. After MAX_TRIES lost
     * races the visitor gets no code rather than one that was never claimed, which could be handed out twice.
     *
     * @return string|null Null when the pool is empty or every attempt lost the race.
     */
    private function claimFreeCode(): ?string
    {
        for ($attempt = 1; $attempt <= self::MAX_TRIES; $attempt++) {
            $row = $this->freeCodeResource->findFreeCode();
            if ($row === null) {
                return null;
            }

            if ($this->freeCodeResource->claim((int)$row['code_id'])) {
                return $row['code'];
            }
        }

        return null;
    }
}
