<?php

declare(strict_types=1);

namespace Vendor\CacheClearByTags\Cron;

use Vendor\CacheClearByTags\Service\Process5minTags;
use Psr\Log\LoggerInterface;
use Throwable;

class ClearCache5minTags
{
    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     * @param Process5minTags $process5minTags
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Process5minTags $process5minTags
    ) {
    }

    /**
     * Execute
     *
     * @return void
     * @throws Throwable
     */
    public function execute(): void
    {
        try {
            $this->process5minTags->cleanCacheByTag();
        } catch (Throwable $e) {
            $msg = sprintf('Error running cron: %s', $e->getMessage());
            $this->logger->error(
                $msg,
                [
                    'context' => __CLASS__
                ]
            );
        }
    }
}
