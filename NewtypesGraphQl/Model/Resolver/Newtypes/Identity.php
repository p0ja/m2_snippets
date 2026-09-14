<?php

declare(strict_types=1);

namespace Vendor\NewtypesGraphQl\Model\Resolver\Newtypes;

use Magento\Framework\GraphQl\Query\Resolver\IdentityInterface;
use Vendor\NewtypesGraphQl\Api\Data\NewtypeInterface;

/**
 * Added: cache identity of the getNewtypes query (@cache in etc/schema.graphqls).
 *
 * Magento_GraphQlCache stores GraphQL GET responses in the full page cache and tags each response with the
 * identities returned here. When an entity is saved, cleaning its tag removes every cached response that contains
 * it, so a query response is never served with stale data.
 */
class Identity implements IdentityInterface
{
    /**
     * Cache tags of a resolved getNewtypes response.
     *
     * $resolvedData is what NewtypeOutput::resolve() returned:
     * ['newtypes' => [['name' => ..., 'model' => NewtypeInterface], ...]]
     *
     * @param array $resolvedData
     * @return string[]
     */
    public function getIdentities(array $resolvedData): array
    {
        $identities = [];

        // TODO(human): build the cache tags for this response from $resolvedData['newtypes']

        return $identities;
    }
}
