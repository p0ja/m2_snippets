<?php

declare(strict_types=1);

namespace M2\Product\Service;

use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\DataObject;
use Stringable;

/**
 * Turns model data into printable strings.
 *
 * Product data mixes scalars, arrays, data objects (stock item, extension attributes) and services cached on the
 * model (type instance, closures). Objects are not serialized: many hold the object manager or closures, which
 * cannot be serialized, and a product can reach itself through its children, so the depth is limited.
 */
class ValueFormatter
{
    private const MAX_DEPTH = 5;

    /**
     * Flatten nested data to "parent.child" keys with string values
     *
     * @param array $data
     * @param string $prefix
     * @param int $depth
     * @return string[]
     */
    public function flatten(array $data, string $prefix = '', int $depth = 0): array
    {
        $rows = [];

        foreach ($data as $key => $value) {
            $path = $prefix === '' ? (string)$key : $prefix . '.' . $key;
            $value = $this->toArray($value);

            if (!is_array($value) || $value === []) {
                $rows[$path] = $this->format($value);
            } elseif ($depth >= self::MAX_DEPTH) {
                $rows[$path] = '[...]';
            } else {
                $rows += $this->flatten($value, $path, $depth + 1);
            }
        }

        return $rows;
    }

    /**
     * Format one value
     *
     * @param mixed $value
     * @return string
     */
    public function format(mixed $value): string
    {
        $value = $this->toArray($value);

        return match (true) {
            $value === null => 'NULL',
            is_bool($value) => $value ? 'true' : 'false',
            is_scalar($value), $value instanceof Stringable => (string)$value,
            is_array($value) => $value === [] ? '[]' : '[' . count($value) . ' items]',
            default => get_debug_type($value),
        };
    }

    /**
     * Data objects as their data array, other values unchanged
     *
     * @param mixed $value
     * @return mixed
     */
    private function toArray(mixed $value): mixed
    {
        return match (true) {
            $value instanceof DataObject => $value->getData(),
            $value instanceof AbstractSimpleObject => $value->__toArray(),
            default => $value,
        };
    }
}
