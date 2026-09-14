<?php

declare(strict_types=1);

namespace M2\MVVM\Controller\Adminhtml\Thing;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Changed: HttpGetActionInterface declares that this redirect only answers GET requests.
 */
class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'M2_MVVM::things';

    /**
     * @inheritDoc
     */
    public function execute(): Redirect
    {
        return $this->resultRedirectFactory->create()->setPath('*/index/index');
    }
}
