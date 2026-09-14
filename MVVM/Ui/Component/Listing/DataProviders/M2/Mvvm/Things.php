<?php

declare(strict_types=1);

namespace M2\MVVM\Ui\Component\Listing\DataProviders\M2\Mvvm;

use M2\MVVM\Model\ResourceModel\Thing\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Listing data provider of m2_mvvm_things.
 */
class Things extends AbstractDataProvider
{
    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
