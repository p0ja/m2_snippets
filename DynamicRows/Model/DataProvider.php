<?php

declare(strict_types=1);

namespace Vendor\DynamicRows\Model;

use Vendor\DynamicCategory\Model\ResourceModel\VendorCollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Data provider of the dynamic_rows form.
 *
 * Changed: the collection is created from its factory. A collection injected directly is a shared instance, so
 * every data provider got the same, possibly already loaded, collection. getData() always returns an array.
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var array|null
     */
    private ?array $loadedData = null;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param VendorCollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        VendorCollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        // Rows under the "stores" key, the value of the scope request field used in the menu URL.

        if ($this->loadedData !== null) {
            return $this->loadedData;
        }

        $this->loadedData = ['stores' => ['dynamic_rows_container' => []]];
        $this->collection->setOrder('value', 'ASC');

        foreach ($this->collection->getItems() as $item) {
            $this->loadedData['stores']['dynamic_rows_container'][] = $item->getData();
        }

        return $this->loadedData;
    }
}
