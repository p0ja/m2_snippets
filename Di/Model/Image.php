<?php

declare(strict_types=1);

namespace Vendor\Di\Model;

/**
 * Changed: the class had no constructor, so the vtArg1 and vtArg2 arguments of the vendorVirtualType virtual type
 * in etc/di.xml were silently ignored. Named constructor parameters receive them, which is what the virtual type
 * example demonstrates.
 */
class Image
{
    /**
     * Constructor
     *
     * @param string $vtArg1
     * @param string $vtArg2
     */
    public function __construct(
        private readonly string $vtArg1 = '',
        private readonly string $vtArg2 = ''
    ) {
    }

    /**
     * Get vt arg1
     *
     * @return string
     */
    public function getVtArg1(): string
    {
        return $this->vtArg1;
    }

    /**
     * Get vt arg2
     *
     * @return string
     */
    public function getVtArg2(): string
    {
        return $this->vtArg2;
    }
}
