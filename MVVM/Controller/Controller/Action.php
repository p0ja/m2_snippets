<?php

declare(strict_types=1);

namespace M2\MVVM\Controller\Controller;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

// Changed: HttpGetActionInterface (extends ActionInterface) limits this page to GET requests.
class Action implements HttpGetActionInterface
{
    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        protected Context $context,
        private readonly PageFactory $pageFactory,
    ) {
    }

    /**
     * In Magento2, each controller has one, and only one, entry point.
     * That’s the execute method
     * https://magento.example/m2_mvvm/controller/action/
     */
    public function execute(): ResultInterface
    {
        // Removed: var_dump(__METHOD__) printed debug output before the page, on a public frontend route.
        return $this->pageFactory->create();
    }
}
