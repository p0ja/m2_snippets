<?php

declare(strict_types=1);

namespace M2\MVVM\Block\Adminhtml\Thing\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Changed:
     * - the third deleteConfirm() argument ({"data": {}}) makes the confirmation send a POST request with the
     *   form key, the way core admin forms delete, instead of navigating to the delete URL with GET;
     * - the message and the URL are escaped with escapeJs(), they are placed inside a JavaScript string.
     */
    public function getButtonData(): array
    {
        if (!$this->getObjectId()) {
            return [];
        }

        $escaper = $this->context->getEscaper();

        return [
            'label' => __('Delete Object'),
            'class' => 'delete',
            'on_click' => sprintf(
                "deleteConfirm('%s', '%s', {\"data\": {}})",
                $escaper->escapeJs(__('Are you sure you want to do this?')),
                $escaper->escapeJs($this->getDeleteUrl())
            ),
            'sort_order' => 20,
        ];
    }
}
