<?php

declare(strict_types=1);

namespace M2\Popup\Setup\Patch\Data;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Cms\Api\GetBlockByIdentifierInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\Store;

/**
 * Creates the CMS block "m2-popup" shown by the popup, unless a block with this identifier already exists.
 * Edit or disable it in Content > Blocks.
 */
class AddPopupCmsBlock implements DataPatchInterface
{
    public const IDENTIFIER = 'm2-popup';

    /**
     * Constructor
     *
     * @param BlockInterfaceFactory $blockFactory
     * @param BlockRepositoryInterface $blockRepository
     * @param GetBlockByIdentifierInterface $getBlockByIdentifier
     */
    public function __construct(
        private readonly BlockInterfaceFactory $blockFactory,
        private readonly BlockRepositoryInterface $blockRepository,
        private readonly GetBlockByIdentifierInterface $getBlockByIdentifier
    ) {
    }

    /**
     * @inheritDoc
     */
    public function apply(): self
    {
        if ($this->blockExists()) {
            return $this;
        }

        /** @var BlockInterface&\Magento\Cms\Model\Block $block */
        $block = $this->blockFactory->create();
        $block->setIdentifier(self::IDENTIFIER)
            ->setTitle('Popup')
            ->setContent('<h2>Welcome!</h2><p>This popup is the CMS block "m2-popup".</p>')
            ->setIsActive(true);
        $block->setData('stores', [Store::DEFAULT_STORE_ID]);

        $this->blockRepository->save($block);

        return $this;
    }

    /**
     * Whether the block was already created, e.g. by an admin before the module was installed
     *
     * @return bool
     */
    private function blockExists(): bool
    {
        try {
            $this->getBlockByIdentifier->execute(self::IDENTIFIER, Store::DEFAULT_STORE_ID);
        } catch (NoSuchEntityException $e) {
            return false;
        }

        return true;
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
