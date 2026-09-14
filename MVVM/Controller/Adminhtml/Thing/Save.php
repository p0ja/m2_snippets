<?php

declare(strict_types=1);

namespace M2\MVVM\Controller\Adminhtml\Thing;

use Exception;
use M2\MVVM\Model\Thing;
use M2\MVVM\Model\ThingFactory;
use M2\MVVM\Model\ThingRepository;
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
 * - the id is cast to int, and the model comes from ThingFactory instead of the object manager;
 * - the data persistor key was "M2_mvvm_thing" on save but "m2_mvvm_thing" when read, so entered data was lost.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'M2_MVVM::things';

    private const DATA_PERSISTOR_KEY = 'm2_mvvm_thing';

    /**
     * Fields the form may change.
     */
    private const ALLOWED_FIELDS = ['title', 'is_active'];

    public function __construct(
        Context $context,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly ThingRepository $objectRepository,
        private readonly ThingFactory $thingFactory
    ) {
        parent::__construct($context);
    }

    /**
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

        $id = (int)$this->getRequest()->getParam('thing_id');
        $fields = array_intersect_key($data, array_flip(self::ALLOWED_FIELDS));

        if (isset($fields['is_active'])) {
            $fields['is_active'] = in_array($fields['is_active'], ['1', 'true', 1, true], true)
                ? Thing::STATUS_ENABLED
                : Thing::STATUS_DISABLED;
        }

        try {
            /** @var Thing $model */
            $model = $id ? $this->objectRepository->getById($id) : $this->thingFactory->create();
            $model->addData($fields);

            $this->objectRepository->save($model);
            $this->messageManager->addSuccessMessage(__('You saved the thing.'));
            $this->dataPersistor->clear(self::DATA_PERSISTOR_KEY);

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

        $this->dataPersistor->set(self::DATA_PERSISTOR_KEY, $fields);

        return $resultRedirect->setPath('*/*/edit', ['thing_id' => $id ?: null]);
    }
}
