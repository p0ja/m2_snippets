<?php

declare(strict_types=1);

namespace Vendor\NewtypesGraphQl\Service;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Changed:
 * - $categoryRepository and $categoryFactory were used without being injected (fatal error), and the return type
 *   was the Adobe Stock CategoryInterface instead of the catalog one;
 * - the category is loaded for the requested store, so names and descriptions are the store's values;
 * - a missing category returns null instead of an empty model, so callers cannot mistake it for a real one.
 */
class GetCategoryById
{
    /**
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
    }

    /**
     * @param int $categoryId
     * @param int $storeId
     * @return CategoryInterface|null
     */
    public function execute(int $categoryId, int $storeId): ?CategoryInterface
    {
        try {
            return $this->categoryRepository->get($categoryId, $storeId);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}
