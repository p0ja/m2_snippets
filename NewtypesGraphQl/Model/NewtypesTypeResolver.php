<?php

declare(strict_types=1);

namespace Vendor\NewtypesGraphQl\Model;

use Magento\Framework\GraphQl\Query\Resolver\TypeResolverInterface;

/**
 * Resolves the NewtypeInterface GraphQL interface to the concrete Newtype type.
 *
 * Changed: a class description instead of an inline {@inheritdoc}, which does not import anything for a class.
 */
class NewtypesTypeResolver implements TypeResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolveType(array $data): string
    {
        return 'Newtype';
    }
}
