<?php

declare(strict_types=1);

namespace M2\Knockout\Model;

use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Data provider of the m2_simple_valid UI component.
 *
 * Changed: the class had no collection, so AbstractDataProvider::getData() failed on a null collection. The
 * example component shows static content and needs no data, so the provider returns an empty set.
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return [];
    }
}
