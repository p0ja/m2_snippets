<?php

declare(strict_types=1);

namespace M2\MVVM\Block\Adminhtml\Thing\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {

        return [
            'label' => __('Back'),
            // Changed: the URL is escaped for the JavaScript string it is placed in.
            'on_click' => sprintf("location.href = '%s';", $this->context->getEscaper()->escapeJs($this->getBackUrl())),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
