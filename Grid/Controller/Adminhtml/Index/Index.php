<?php

declare(strict_types=1);

namespace Dev\Grid\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Changed:
 * - ADMIN_RESOURCE was not set, so the inherited Magento_Backend::admin let every admin role open the category
 *   listing; it now requires the resource of its menu item (etc/adminhtml/menu.xml);
 * - HttpGetActionInterface declares that the page only answers GET requests;
 * - the active menu is the menu item's parent, catalog categories, instead of catalog products.
 */
class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Magento_Catalog::categories';

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        private readonly PageFactory $pageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Magento_Catalog::catalog_categories');
        $resultPage->getConfig()
            ->getTitle()
            ->prepend(__('Admin Grid Example'));

        return $resultPage;
    }
}
