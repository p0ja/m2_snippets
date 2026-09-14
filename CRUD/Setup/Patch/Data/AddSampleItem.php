<?php

declare(strict_types=1);

namespace M2\CRUD\Setup\Patch\Data;

use M2\CRUD\Api\Data\ItemInterfaceFactory;
use M2\CRUD\Api\ItemRepositoryInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Added: data patch that creates the sample item. It replaces the empty Setup/InstallData.php (deprecated since
 * 2.3) and the insert that the frontend block used to run on every page view. A data patch runs once during
 * setup:upgrade and is recorded in the patch_list table.
 */
class AddSampleItem implements DataPatchInterface
{
    /**
     * Constructor
     *
     * @param ItemInterfaceFactory $itemFactory
     * @param ItemRepositoryInterface $itemRepository
     */
    public function __construct(
        private readonly ItemInterfaceFactory $itemFactory,
        private readonly ItemRepositoryInterface $itemRepository
    ) {
    }

    /**
     * @inheritDoc
     */
    public function apply(): self
    {
        $item = $this->itemFactory->create();
        $item->setSku('sample-001')
            ->setTitle('Finish my Magento article')
            ->setIsActive(true);

        $this->itemRepository->save($item);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
