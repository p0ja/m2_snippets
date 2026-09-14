<?php

declare(strict_types=1);

namespace M2\Product\Console\Command;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use M2\Product\Service\ValueFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Shared arguments and product loading of the product commands.
 */
abstract class AbstractProductCommand extends Command
{
    private const ARGUMENT_PRODUCT_ID = 'product_id';
    private const ARGUMENT_STORE_ID = 'store_id';

    /**
     * Constructor
     *
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param ValueFormatter $valueFormatter
     * @param string|null $name
     */
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly StoreManagerInterface $storeManager,
        protected readonly ValueFormatter $valueFormatter,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * Print the product
     *
     * @param ProductInterface $product
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    abstract protected function printProduct(
        ProductInterface $product,
        InputInterface $input,
        OutputInterface $output
    ): void;

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->addArgument(self::ARGUMENT_PRODUCT_ID, InputArgument::REQUIRED, 'Product ID');
        $this->addArgument(
            self::ARGUMENT_STORE_ID,
            InputArgument::OPTIONAL,
            'Store ID for store view values, the default (admin) values when omitted'
        );

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $productId = (int)$input->getArgument(self::ARGUMENT_PRODUCT_ID);
        $storeId = $input->getArgument(self::ARGUMENT_STORE_ID);

        try {
            // getStore() throws NoSuchEntityException for an unknown store, so a typo does not silently print
            // the default values.
            $storeId = $storeId === null ? null : (int)$this->storeManager->getStore((int)$storeId)->getId();
            $product = $this->productRepository->getById($productId, false, $storeId);
        } catch (NoSuchEntityException $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return Command::FAILURE;
        }

        $this->printProduct($product, $input, $output);

        return Command::SUCCESS;
    }
}
