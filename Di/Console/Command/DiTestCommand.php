<?php

declare(strict_types=1);

namespace Vendor\Di\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vendor\Di\Model\Image;
use Vendor\Di\Model\TestInterface;

/**
 * Playground for the constructor arguments configured in etc/di.xml.
 *
 * Changed:
 * - $arg1 and $arg2 were typed string, but di.xml injects objects (a User and the vendorVirtualType Image), which
 *   is a TypeError; they are typed with what di.xml passes;
 * - the result is printed through $output instead of var_dump();
 * - $name is not redeclared as a property, it only goes to the parent constructor.
 */
class DiTestCommand extends Command
{
    /**
     * Constructor
     *
     * @param TestInterface $arg1 object argument, Vendor\Di\Model\User in di.xml
     * @param Image $arg2 virtual type argument, vendorVirtualType in di.xml
     * @param string|null $name
     */
    public function __construct(
        private readonly TestInterface $arg1,
        private readonly Image $arg2,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('vendor:di');
        $this->setDescription('Sample playground for Vendor_Di module');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Playground code here...
        $output->writeln('arg1: ' . get_class($this->arg1));
        $output->writeln(sprintf(
            'arg2: %s (vtArg1=%s, vtArg2=%s)',
            get_class($this->arg2),
            $this->arg2->getVtArg1(),
            $this->arg2->getVtArg2()
        ));

        return Command::SUCCESS;
    }
}
