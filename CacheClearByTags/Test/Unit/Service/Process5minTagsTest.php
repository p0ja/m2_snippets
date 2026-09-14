<?php

declare(strict_types=1);

namespace Vendor\CacheClearByTags\Test\Unit\Service;

use Magento\Framework\App\CacheInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Vendor\CacheClearByTags\Service\Process5minTags;
use Vendor\CacheClearByTags\Tags\ClearTagsList;

/**
 * Added: unit test example (PHPUnit 10, as used by Magento 2.4.8). Run from the Magento root with
 * vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/Vendor/CacheClearByTags/Test/Unit
 */
class Process5minTagsTest extends TestCase
{
    /**
     * @var CacheInterface|MockObject
     */
    private $cache;

    /**
     * @var Process5minTags
     */
    private Process5minTags $process5minTags;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->cache = $this->createMock(CacheInterface::class);
        $this->process5minTags = new Process5minTags($this->cache);
    }

    /**
     * Test cleans only the configured tags
     *
     * @return void
     */
    public function testCleansOnlyTheConfiguredTags(): void
    {
        $this->cache->expects($this->once())
            ->method('clean')
            ->with(ClearTagsList::TAGS_EVERY_5MIN)
            ->willReturn(true);

        $this->cache->expects($this->never())->method('load');

        $this->assertTrue($this->process5minTags->cleanCacheByTag());
    }

    /**
     * Test reports when the cache was not cleaned
     *
     * @return void
     */
    public function testReportsWhenTheCacheWasNotCleaned(): void
    {
        $this->cache->method('clean')->willReturn(false);

        $this->assertFalse($this->process5minTags->cleanCacheByTag());
    }
}
