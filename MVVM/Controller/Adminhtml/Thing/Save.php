<?php

declare(strict_types=1);

namespace M2\MVVM\Controller\Adminhtml\Thing;

use Exception;
use M2\MVVM\Api\Data\ThingInterface;
use M2\MVVM\Api\Data\ThingInterfaceFactory;
use M2\MVVM\Api\ThingRepositoryInterface;
use M2\MVVM\Model\Thing\DataProvider;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Changed:
 * - HttpPostActionInterface: the action changes data, so it only accepts POST (the form posts with its form key);
 * - only the fields of the form are copied to the model. setData() with the whole POST body replaced every model
 *   field, including ones the form does not have, such as creation_time;
 * - the id is cast to int, and the model comes from a factory instead of the object manager;
 * - the data persistor key was "M2_mvvm_thing" on save but "m2_mvvm_thing" when read, so entered data was lost;
 * - depends on ThingRepositoryInterface and ThingInterfaceFactory (service contracts) instead of the concrete classes.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'M2_MVVM::things';

    /**
     * Constructor
     *
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ThingRepositoryInterface $objectRepository
     * @param ThingInterfaceFactory $thingFactory
     */
    public function __construct(
        Context $context,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly ThingRepositoryInterface $objectRepository,
        private readonly ThingInterfaceFactory $thingFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(): Redirect
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $id = (int)$this->getRequest()->getParam(ThingInterface::THING_ID);

        // Only the form fields are applied, through the ThingInterface setters.
        $fields = [];
        if (isset($data[ThingInterface::TITLE])) {
            $fields[ThingInterface::TITLE] = trim((string)$data[ThingInterface::TITLE]);
        }
        if (isset($data[ThingInterface::IS_ACTIVE])) {
            $fields[ThingInterface::IS_ACTIVE] = in_array(
                $data[ThingInterface::IS_ACTIVE],
                ['1', 'true', 1, true],
                true
            );
        }

        try {
            $model = $id ? $this->objectRepository->getById($id) : $this->thingFactory->create();
            if (isset($fields[ThingInterface::TITLE])) {
                $model->setTitle($fields[ThingInterface::TITLE]);
            }
            if (isset($fields[ThingInterface::IS_ACTIVE])) {
                $model->setIsActive($fields[ThingInterface::IS_ACTIVE]);
            }

            $this->objectRepository->save($model);
            $this->messageManager->addSuccessMessage(__('You saved the thing.'));
            $this->dataPersistor->clear(DataProvider::DATA_PERSISTOR_KEY);

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', [
                    'thing_id' => $model->getId(),
                    '_current' => true,
                ]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the data.'));
        }

        $this->dataPersistor->set(DataProvider::DATA_PERSISTOR_KEY, $fields);

        return $resultRedirect->setPath('*/*/edit', ['thing_id' => $id ?: null]);
    }
}
