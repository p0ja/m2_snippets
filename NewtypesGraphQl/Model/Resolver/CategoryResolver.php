<?php

declare(strict_types=1);

namespace Vendor\NewtypesGraphQl\Model\Resolver;

use Magento\Catalog\Model\Category;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Query\Uid;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use Vendor\NewtypesGraphQl\Api\Data\NewtypeInterface;
use Vendor\NewtypesGraphQl\Service\GetCategoryById;

class CategoryResolver implements ResolverInterface
{
    /**
     * Constructor
     *
     * @param GetCategoryById $getCategoryById
     * @param Uid $uidEncoder
     */
    public function __construct(
        private readonly GetCategoryById $getCategoryById,
        private readonly Uid $uidEncoder
    ) {
    }

    /**
     * @inheritDoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ) {
        // Changed:
        // - the category is returned only when it is active and belongs to the current store's category tree. The
        //   resolver returned any category by id, so a public query exposed disabled categories and categories of
        //   other stores;
        // - the field is a list ([CategoryData]), so a list is returned, and uid is encoded like core GraphQL uids;
        // - ?array for $value and $args: implicitly nullable parameters are deprecated in PHP 8.4, supported by
        //   Magento 2.4.8;
        // - $value is checked before array_key_exists(), which throws on null.

        if (!isset($value['model']) || !$value['model'] instanceof NewtypeInterface) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        /** @var ContextInterface $context */
        $store = $context->getExtensionAttributes()->getStore();

        /** @var Category|null $category */
        $category = $this->getCategoryById->execute((int)$value['model']->getCategoryId(), (int)$store->getId());

        if ($category === null || !$category->getIsActive() || !$this->isInStoreTree($category, $store)) {
            return [];
        }

        return [[
            'uid' => $this->uidEncoder->encode((string)$category->getId()),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
            'path' => $category->getUrlPath(),
        ]];
    }

    /**
     * Whether the category lies under the root category of the store.
     *
     * @param Category $category
     * @param \Magento\Store\Model\Store $store
     * @return bool
     */
    private function isInStoreTree(Category $category, $store): bool
    {
        $rootCategoryId = (string)$store->getRootCategoryId();

        return in_array($rootCategoryId, explode('/', (string)$category->getPath()), true);
    }
}
