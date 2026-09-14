<?php

declare(strict_types=1);

namespace M2\MVVM\Model\Thing;

use M2\MVVM\Model\ResourceModel\Thing\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Form data provider of m2_mvvm_things_form.
 *
 * Changed: the redeclared $meta property (private, shadowing the parent's protected one) and the empty
 * prepareMeta() hook were removed; the data persistor key is a constant shared with the Save controller.
 */
class DataProvider extends AbstractDataProvider
{
    public const DATA_PERSISTOR_KEY = 'm2_mvvm_thing';

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
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        private readonly DataPersistorInterface $dataPersistor,
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
        if ($this->loadedData !== null) {
            return $this->loadedData;
        }

        $this->loadedData = [];
        foreach ($this->collection->getItems() as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
        }

        $data = $this->dataPersistor->get(self::DATA_PERSISTOR_KEY);
        if (!empty($data)) {
            $item = $this->collection->getNewEmptyItem();
            $item->setData($data);
            $this->loadedData[$item->getId()] = $item->getData();
            $this->dataPersistor->clear(self::DATA_PERSISTOR_KEY);
        }

        return $this->loadedData;
    }
}
