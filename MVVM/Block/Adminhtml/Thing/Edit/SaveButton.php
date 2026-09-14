<?php

declare(strict_types=1);

namespace M2\MVVM\Block\Adminhtml\Thing\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save Object'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'save',
                    ]
                ],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
