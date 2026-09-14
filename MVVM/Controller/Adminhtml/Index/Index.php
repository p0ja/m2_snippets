<?php

declare(strict_types=1);

namespace M2\MVVM\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Changed: HttpGetActionInterface declares that this page only answers GET requests; the router rejects other
 * methods before the action is created. The unused Page import was removed.
 */
class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'M2_MVVM::things';

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory,
    ) {
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute(): ResultInterface
    {
        return $this->resultPageFactory->create();
    }
}
