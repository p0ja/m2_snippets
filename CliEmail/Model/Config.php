<?php

declare(strict_types=1);

namespace M2\CliEmail\Model;

use M2\CliEmail\Config\Parameters;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Added: reads the module configuration. It replaces M2\CliEmail\Helper\Data: helpers extending AbstractHelper are
 * discouraged since they bundle a large Context and tend to collect unrelated methods. The old helper also never
 * called parent::__construct(), so $this->scopeConfig was null, and getTemplate()/getSender() were private methods
 * called from another class.
 */
class Config
{
    /**
     * Constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Is enabled
     *
     * @param int $storeId
     * @return bool
     */
    public function isEnabled(int $storeId): bool
    {
        return $this->scopeConfig->isSetFlag(Parameters::EMAIL_SERVICE_ENABLE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get template id
     *
     * @param int $storeId
     * @return string
     */
    public function getTemplateId(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(Parameters::EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Sender identity code, e.g. "general".
     *
     * @param int $storeId
     * @return string
     */
    public function getSender(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(Parameters::EMAIL_SENDER, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
