<?php

declare(strict_types=1);

namespace Vendor\FreeCodes\Api;

/**
 * Gives the current visitor one code from the pool.
 *
 * @api
 */
interface ClaimFreeCodeInterface
{
    /**
     * The code of this session, claiming a free one on the first call
     *
     * @return string|null Null when the pool is empty.
     */
    public function execute(): ?string;
}
