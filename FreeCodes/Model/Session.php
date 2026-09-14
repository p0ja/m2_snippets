<?php

declare(strict_types=1);

namespace Vendor\FreeCodes\Model;

use Magento\Framework\Session\SessionManager;

/**
 * Session of the free codes, with its own storage namespace set in etc/di.xml, so the claimed code is kept under
 * $_SESSION['free_codes'] and does not mix with the customer or checkout session.
 *
 * Typed accessors instead of the magic getX()/setX() of SessionManager::__call(). The constructor starts the PHP
 * session, so only the claim controller uses this class, never a block of a cached page.
 */
class Session extends SessionManager
{
    private const CLAIMED_CODE = 'claimed_code';

    /**
     * Code claimed in this session
     *
     * @return string|null
     */
    public function getClaimedCode(): ?string
    {
        $code = $this->storage->getData(self::CLAIMED_CODE);

        return is_string($code) && $code !== '' ? $code : null;
    }

    /**
     * Remember the claimed code
     *
     * @param string $code
     * @return void
     */
    public function setClaimedCode(string $code): void
    {
        $this->storage->setData(self::CLAIMED_CODE, $code);
    }
}
