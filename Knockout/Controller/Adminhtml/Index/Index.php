<?php

declare(strict_types=1);

namespace M2\Knockout\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Changed: ADMIN_RESOURCE named the menu item id (M2_Knockout::M2_knockout_menu), which is not an ACL resource,
 * so only administrators with full access could open the page. It now uses the resource from etc/acl.xml.
 * The _isAllowed() override was removed: the argument it passed to parent::_isAllowed() was ignored, the parent
 * already checks ADMIN_RESOURCE.
 */
class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'M2_Knockout::menu_1';

    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        return $this->resultPageFactory->create();
    }
}
