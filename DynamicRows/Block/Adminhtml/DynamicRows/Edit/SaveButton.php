<?php

declare(strict_types=1);

namespace Vendor\DynamicRows\Block\Adminhtml\DynamicRows\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Changed: no longer extends Magento\CatalogRule\Block\Adminhtml\Edit\GenericButton. Borrowing a block from the
 * Catalog Rule module tied this snippet to that module, and the button needs no URL helper: it submits the form.
 */
class SaveButton implements ButtonProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getButtonData(): array
    {
        // The button submits the UI form (POST to the form's submitUrl with the rows and the form key).
        // setLocation() opened the save URL with GET and without any row data, which made the save action delete
        // every row; the JavaScript string also had a syntax error ('').

        return [
           'label' => __('Save Rows'),
           'class' => 'save primary',
           'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save']],
                    'form-role' => 'save',
           ],
           'sort_order' => 90,
        ];
    }
}
