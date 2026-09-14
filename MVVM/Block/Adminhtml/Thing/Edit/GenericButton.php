<?php

declare(strict_types=1);

namespace M2\MVVM\Block\Adminhtml\Thing\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{
    /**
     * Constructor
     *
     * @param Context $context
     */
    public function __construct(
        protected Context $context,
    ) {
    }

    /**
     * Get back url
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }

    /**
     * Get url
     *
     * @param string|null $route
     * @param array $params
     * @return string
     */
    public function getUrl(?string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    /**
     * Get delete url
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', [
            'object_id' => $this->getObjectId()
        ]);
    }

    /**
     * Get object id
     *
     * @return string|null
     */
    public function getObjectId()
    {
        return $this->context->getRequest()->getParam('thing_id');
    }
}
