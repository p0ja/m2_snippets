<?php

declare(strict_types=1);

namespace M2\CRUD\Command;

use M2\CRUD\Service\GetFilteredProductList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListSku extends Command
{
    private const ARG_SKU_PART = 'skuLike';

    /**
     * Changed: ?string, implicitly nullable parameters are deprecated in PHP 8.4, supported by Magento 2.4.8.
     *
     * @param GetFilteredProductList $getFilteredProductList
     * @param string|null $name
     */
    public function __construct(
        private readonly GetFilteredProductList $getFilteredProductList,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('vendor:search:items');
        $this->setDescription('Lists the CRUD example items whose SKU matches a LIKE pattern, e.g. "sample%".');
        $this->addArgument(
            self::ARG_SKU_PART,
            InputArgument::REQUIRED,
            'SKU LIKE pattern to search for'
        );

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Changed: prints through $output instead of echo (so --quiet and output redirection work), and uses the
        // ItemInterface getters; getItemsSku() did not exist and returned null through the magic __call().

        $skuPart = (string)$input->getArgument(self::ARG_SKU_PART);

        foreach ($this->getFilteredProductList->execute($skuPart) as $item) {
            $output->writeln(sprintf('[%s] %s', $item->getSku(), $item->getTitle()));
        }

        return Command::SUCCESS;
    }
}
