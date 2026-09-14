<?php

declare(strict_types=1);

namespace M2\CRUD\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Changed: HttpGetActionInterface limits the page to GET requests, and the missing ResultInterface import made
 * the return type resolve to a non-existent class in this namespace (TypeError on every request).
 */
class Index extends Action implements HttpGetActionInterface
{

    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        return $this->resultPageFactory->create();
    }
}
