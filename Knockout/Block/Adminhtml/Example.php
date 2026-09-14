<?php

declare(strict_types=1);

namespace M2\Knockout\Block\Adminhtml;

use Magento\Backend\Block\Template;

class Example extends Template
{
    /**
     * To html
     */
    public function toHtml()
    {
        return '<h1>PHP Block Rendered in JS</h1>';
    }
}
