<?php

declare(strict_types=1);

namespace Vendor\CacheClearByTags\Command;

use Vendor\CacheClearByTags\Service\Process5minTags;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunClearCache extends Command
{
    /**
     * Constructor
     *
     * @param Process5minTags $process5minTags
     */
    public function __construct(
        private readonly Process5minTags $process5minTags
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('vendor:cache:clear');
        $this->setDescription('Clears cache by tags.');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Changed: returns FAILURE when cleaning fails or the cache reports it did not clean, so scripts and CI can
        // detect it; it always returned SUCCESS and printed the error as normal output.

        try {
            if (!$this->process5minTags->cleanCacheByTag()) {
                $output->writeln('<error>The cache was not cleaned.</error>');

                return Command::FAILURE;
            }
        } catch (Exception $e) {
            $output->writeln(sprintf('<error>Error running command: %s</error>', $e->getMessage()));

            return Command::FAILURE;
        }

        $output->writeln('<info>Cache entries with the 5 minute tags have been cleaned.</info>');

        return Command::SUCCESS;
    }
}
