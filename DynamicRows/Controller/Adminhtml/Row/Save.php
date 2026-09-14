<?php

declare(strict_types=1);

namespace Vendor\DynamicRows\Controller\Adminhtml\Row;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Vendor\DynamicCategory\Model\CategoryRuleFactory;
use Vendor\DynamicCategory\Model\ResourceModel\CategoryRuleResourceFactory;
use Vendor\DynamicRows\Model\Source\Condition;

/**
 * Changed:
 * - HttpPostActionInterface: the action deletes and rewrites all rows, it must not run on a GET request;
 * - ADMIN_RESOURCE is checked by the backend before execute(), replacing the _isAllowed() override and the
 *   manual check, which silently redirected instead of showing "access denied";
 * - the submitted rows are read and validated before anything is deleted. The old code deleted all rows first,
 *   so a request without row data (the GET save button) wiped the table;
 * - each row is limited to its form fields, and the condition must be one of the source model values;
 * - unexpected errors show a generic message instead of the raw exception text.
 */
class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Vendor_DynamicRows::dynamic_rows';

    /**
     * Fields of one dynamic row in view/adminhtml/ui_component/dynamic_rows.xml.
     */
    private const ALLOWED_FIELDS = ['condition', 'value'];

    /**
     * Constructor
     *
     * @param Context $context
     * @param CategoryRuleFactory $dynamicRowFactory
     * @param CategoryRuleResourceFactory $dynamicRowResource
     * @param Condition $conditionSource
     */
    public function __construct(
        Context $context,
        private readonly CategoryRuleFactory $dynamicRowFactory,
        private readonly CategoryRuleResourceFactory $dynamicRowResource,
        private readonly Condition $conditionSource
    ) {
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create()->setPath('*/*/index/scope/stores');
        $dynamicRowData = $this->getRequest()->getPostValue('dynamic_rows_container');

        if (!is_array($dynamicRowData)) {
            $this->messageManager->addErrorMessage(__('No rows were submitted, nothing has been changed.'));

            return $resultRedirect;
        }

        $allowedConditions = array_column($this->conditionSource->toOptionArray(), 'value');
        $rows = [];
        foreach ($dynamicRowData as $dynamicRowDatum) {
            $row = array_intersect_key((array)$dynamicRowDatum, array_flip(self::ALLOWED_FIELDS));
            if (!in_array($row['condition'] ?? null, $allowedConditions, true)) {
                    $this->messageManager->addErrorMessage(
                        __('A row has an invalid condition, nothing has been changed.')
                    );

                    return $resultRedirect;
            }
            $rows[] = $row;
        }

        try {
            $dynamicRowResource = $this->dynamicRowResource->create();
            $dynamicRowResource->deleteDynamicRows();

            // Changed: rows are saved through the resource model; AbstractModel::save() is deprecated.
            foreach ($rows as $row) {
                    $dynamicRowResource->save($this->dynamicRowFactory->create()->addData($row));
            }

            $this->messageManager->addSuccessMessage(__('Rows have been saved successfully'));
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the rows.'));
        }

        return $resultRedirect;
    }
}
