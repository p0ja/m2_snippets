<?php

declare(strict_types=1);

namespace Vendor\NewtypesGraphQl\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Vendor\NewtypesGraphQl\Api\Data\NewtypeInterface;
use Vendor\Newtypes\Enum\Priorities;

/**
 * Resolves the priority of a newtype to the name of its Priorities enum case.
 *
 * Changed:
 * - NewtypeInterface is this module's interface (Vendor\NewtypesGraphQl\Api\Data), the same one the data provider
 *   and CategoryResolver use; the Vendor\Newtypes\Api\Data interface never matched the models passed in;
 * - Priorities::tryFrom() instead of from(): an unknown stored value returned a ValueError (an internal server
 *   error for the whole query); it now fails as a GraphQL error for this field;
 * - ?array parameters (implicitly nullable ones are deprecated in PHP 8.4), and $value is checked before
 *   array_key_exists(), which throws on null.
 * The Priorities enum belongs to the Vendor_Newtypes module, which is not part of this repository.
 */
class PriorityResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ): string {
        if (!isset($value['model']) || !$value['model'] instanceof NewtypeInterface) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $priority = Priorities::tryFrom($value['model']->getPriority());
        if ($priority === null) {
            throw new LocalizedException(__('The newtype has an unknown priority.'));
        }

        return $priority->name;
    }
}
