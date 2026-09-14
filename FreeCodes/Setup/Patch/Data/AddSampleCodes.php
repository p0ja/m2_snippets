<?php

declare(strict_types=1);

namespace Vendor\FreeCodes\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Vendor\FreeCodes\Model\ResourceModel\FreeCode as FreeCodeResource;

/**
 * Fills the pool with five sample codes, so the widget can be tried right after setup:upgrade.
 */
class AddSampleCodes implements DataPatchInterface
{
    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup
    ) {
    }

    /**
     * @inheritDoc
     */
    public function apply(): self
    {
        $rows = [];
        for ($i = 1; $i <= 5; $i++) {
            $rows[] = ['code' => sprintf('SAMPLE-%04d', $i)];
        }

        $this->moduleDataSetup->getConnection()->insertOnDuplicate(
            $this->moduleDataSetup->getTable(FreeCodeResource::TABLE_NAME),
            $rows,
            ['code']
        );

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
