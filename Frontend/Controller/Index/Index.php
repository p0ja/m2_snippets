<?php

declare(strict_types=1);

namespace M2\Frontend\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Changed: implements HttpGetActionInterface directly instead of extending Magento\Framework\App\Action\Action,
 * which is deprecated since 2.4; the controller injects only the page factory it uses.
 */
class Index implements HttpGetActionInterface
{
    /**
     * Constructor
     *
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        private readonly PageFactory $resultPageFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(): ResultInterface
    {
        return $this->resultPageFactory->create();
    }
}
