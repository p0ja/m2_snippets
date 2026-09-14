<?php

declare(strict_types=1);

namespace M2\Product\Console\Command;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\DataObject;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Prints everything the loaded product model holds in its data array, nested values as dotted keys.
 *
 * Example: bin/magento vendor:product:data 1 1
 */
class ProductDataShowCommand extends AbstractProductCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('vendor:product:data');
        $this->setDescription('Shows the data array of a product');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function printProduct(ProductInterface $product, InputInterface $input, OutputInterface $output): void
    {
        /** @var ProductInterface&DataObject $product */
        $rows = [];
        foreach ($this->valueFormatter->flatten($product->getData()) as $key => $value) {
            $rows[] = [$key, $value];
        }

        (new Table($output))
            ->setHeaders(['Key', 'Value'])
            ->setRows($rows)
            ->render();
    }
}
