<?php

declare(strict_types=1);

namespace M2\MVVM\Controller\Adminhtml\Thing;

use M2\MVVM\Model\ThingRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Changed: HttpPostActionInterface, a delete must not be reachable with a plain GET link (the delete button
 * now posts, see DeleteButton). The id is cast to int, and unexpected errors show a generic message instead
 * of the raw exception text, which could contain SQL details.
 */
class Delete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'M2_MVVM::things';

    /**
     * @param Context $context
     * @param ThingRepository $objectRepository
     */
    public function __construct(
        Context $context,
        private readonly ThingRepository $objectRepository
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        $id = (int)$this->getRequest()->getParam('object_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$id) {
            $this->messageManager->addErrorMessage(__('We can not find an object to delete.'));

            return $resultRedirect->setPath('*/*/');
        }

        try {
            $this->objectRepository->deleteById($id);
            $this->messageManager->addSuccessMessage(__('You have deleted the object.'));

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while deleting the object.'));
        }

        return $resultRedirect->setPath('*/*/edit', ['thing_id' => $id]);
    }
}
