<?php

declare(strict_types=1);

namespace M2\Product\Console\Command;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Prints the EAV attributes of the product's attribute set with their values.
 *
 * Example: bin/magento vendor:product:attributes 1 --hide-empty
 */
class ProductAttributesShowCommand extends AbstractProductCommand
{
    private const OPTION_HIDE_EMPTY = 'hide-empty';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('vendor:product:attributes');
        $this->setDescription('Shows the attributes of a product with their values');
        $this->addOption(self::OPTION_HIDE_EMPTY, null, InputOption::VALUE_NONE, 'Skip attributes without a value');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function printProduct(ProductInterface $product, InputInterface $input, OutputInterface $output): void
    {
        /** @var Product $product */
        $hideEmpty = (bool)$input->getOption(self::OPTION_HIDE_EMPTY);
        $rows = [];

        // getAttributes() returns the attribute models of the product's attribute set, keyed by attribute code.
        foreach ($product->getAttributes() as $code => $attribute) {
            $value = $product->getData($code);
            if ($hideEmpty && ($value === null || $value === '' || $value === [])) {
                continue;
            }

            $rows[$code] = [
                $code,
                (string)$attribute->getDefaultFrontendLabel(),
                (string)$attribute->getFrontendInput(),
                $this->valueFormatter->format($value),
            ];
        }
        ksort($rows);

        (new Table($output))
            ->setHeaders(['Code', 'Label', 'Input', 'Value'])
            ->setRows(array_values($rows))
            ->render();
    }
}
