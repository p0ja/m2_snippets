<?php

declare(strict_types=1);

namespace Vendor\FreeCodes\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

/**
 * CMS widget declared in etc/widget.xml; the widget parameters (title, note) are the block data.
 *
 * The block renders only the button and the claim URL, the same HTML for every visitor, so it can stay in the
 * full page cache.
 */
class Codes extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'Vendor_FreeCodes::widget/codes.phtml';

    /**
     * URL of the claim controller
     *
     * @return string
     */
    public function getClaimUrl(): string
    {
        return $this->getUrl('freecodes/code/claim');
    }
}
