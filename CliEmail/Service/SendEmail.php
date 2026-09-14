<?php

declare(strict_types=1);

namespace M2\CliEmail\Service;

use Exception;
use M2\CliEmail\Config\Parameters;
use M2\CliEmail\Model\Config;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Mail\TransportInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Changed:
 * - TransportBuilder, StateInterface and LoggerInterface were used without imports (fatal error);
 * - configuration comes from M2\CliEmail\Model\Config instead of a helper, the store from StoreManagerInterface;
 * - prepareEmailTemplate() declared a string return type but returns the transport;
 * - the sender is resolved for the store (setFromByScope's second argument);
 * - inline translation is resumed in finally, so a failed send no longer leaves it suspended;
 * - the name is optional, the command option can be omitted;
 * - returns whether the email was sent, so the command can report it.
 */
class SendEmail
{
    /**
     * Constructor
     *
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Config $config,
        private readonly StoreManagerInterface $storeManager,
        private readonly TransportBuilder $transportBuilder,
        private readonly StateInterface $inlineTranslation,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Send mail
     *
     * @param string|null $name
     * @return bool
     */
    public function sendMail(?string $name = null): bool
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        if (!$this->config->isEnabled($storeId)) {
            return false;
        }

        $this->inlineTranslation->suspend();
        try {
            $this->prepareEmailTemplate((string)$name, $storeId)->sendMessage();

            return true;
        } catch (Exception $e) {
            $this->logger->critical(sprintf('[%s] Error sending email: %s', __CLASS__, $e->getMessage()));

            return false;
        } finally {
            $this->inlineTranslation->resume();
        }
    }

    /**
     * Prepare email template
     *
     * @param string $name
     * @param int $storeId
     * @return TransportInterface
     */
    private function prepareEmailTemplate(string $name, int $storeId): TransportInterface
    {
        return $this->transportBuilder
            ->setTemplateIdentifier($this->config->getTemplateId($storeId))
            ->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => $storeId,
            ])
            ->setTemplateVars([
                'name' => $name,
                'message_1' => Parameters::CUSTOM_MESSAGE_1,
                'message_2' => Parameters::CUSTOM_MESSAGE_2,
                'store' => $this->storeManager->getStore($storeId),
            ])
            ->setFromByScope($this->config->getSender($storeId), $storeId)
            ->addTo(Parameters::EMAIL_RECEIVER)
            ->addBcc(Parameters::EMAIL_RECEIVERS_BCC)
            ->getTransport();
    }
}
