<?php

declare(strict_types=1);

/**
 * Added: without registration.php and etc/module.xml Magento never loaded this directory as a module, so the
 * command, the configuration and the email template were unavailable.
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'M2_CliEmail',
    __DIR__
);
