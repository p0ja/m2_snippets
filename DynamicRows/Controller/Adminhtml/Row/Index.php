<?php

declare(strict_types=1);

namespace Vendor\DynamicRows\Controller\Adminhtml\Row;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Changed: extended the Import/Export "Export" index controller, so the page was protected by
 * Magento_ImportExport::export instead of this module's ACL resource: roles allowed to export could open it,
 * and roles granted only "Dynamic Rows" could not. It now extends the backend action with its own resource.
 */
class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Vendor_DynamicRows::dynamic_rows';

    /**
     * Execute
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Vendor_DynamicRows::dynamic_rows');
        $resultPage->getConfig()->getTitle()->prepend(__('Dynamic Rows'));
        $resultPage->addBreadcrumb(__('Dynamic Rows'), __('Dynamic Rows'));

        return $resultPage;
    }
}
