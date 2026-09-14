<?php

declare(strict_types=1);

namespace M2\MVVM\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Added: the view model of the MVVM frontend example. It replaces M2\MVVM\Block\Main, which set template data in
 * _prepareLayout(). In Magento's MVVM the layout XML attaches the view model to a generic template block, the
 * template (view) reads it, and the block class stays the framework one.
 */
class Greeting implements ArgumentInterface
{
    /**
     * Constructor
     *
     * @param RequestInterface $request
     */
    public function __construct(
        private readonly RequestInterface $request
    ) {
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return (string)__('Message from the Greeting view model');
    }

    /**
     * The "name" request parameter; the template must escape it.
     *
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->request->getParam('name', '');
    }
}
