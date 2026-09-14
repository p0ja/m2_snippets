<?php

declare(strict_types=1);

namespace M2\Frontend\Block;

use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Head extends Template
{
    public function __construct(
        Context $context,
        private readonly Repository $assetRepository,
        array $data = [],
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Added: the template read the private $assetRepository property directly, which fails from a template.
     * The block exposes only the URL of a module asset.
     *
     * @param string $fileId module asset id, e.g. M2_Frontend::test.js
     * @return string
     */
    public function getAssetUrl(string $fileId): string
    {
        return $this->assetRepository->getUrl($fileId);
    }
}
