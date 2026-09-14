<?php

declare(strict_types=1);

namespace Vendor\CacheClearByTags\Service;

use Vendor\CacheClearByTags\Tags\ClearTagsList;
use Magento\Framework\App\CacheInterface;

class Process5minTags
{
    /**
     * @param CacheInterface $cache
     */
    public function __construct(
        private readonly CacheInterface $cache
    ) {
    }

    /**
     * Cleans the cache entries tagged with the tags listed in ClearTagsList::TAGS_EVERY_5MIN.
     *
     * Changed: removed the hard-coded cache key "customer_rma_list__0_2219-009-034-004-0004", which looked like an
     * identifier copied from a real shop. It was also passed to load(), which returns the cached value, not a tag,
     * so the value (or false) was added to the tag list. Tags to clean belong in ClearTagsList.
     *
     * @return bool
     */
    public function cleanCacheByTag(): bool
    {
        return $this->cache->clean(ClearTagsList::TAGS_EVERY_5MIN);
    }
}
