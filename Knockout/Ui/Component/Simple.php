<?php

declare(strict_types=1);

namespace M2\Knockout\Ui\Component;

use Magento\Ui\Component\AbstractComponent;

class Simple extends AbstractComponent
{
    public const NAME = 'html_content_m2_simple_valid';

    /**
     * @inheritDoc
     */
    public function getComponentName(): string
    {
        // Changed: returned self::getName(), a static call of an instance method, which is an Error in PHP 8. The
        // component name is the NAME constant.

        return static::NAME;
    }
}
