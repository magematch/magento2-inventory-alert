<?php
declare(strict_types=1);

namespace Rameera\LowStockNotification\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

class Email extends AbstractHelper
{
    private const CONFIG_PATH_TEMPLATE = 'cataloginventory/stock_notification/template';
    private const CONFIG_PATH_RECIPIENT = 'cataloginventory/stock_notification/admin_email';
    private const DEFAULT_TEMPLATE_ID = 'cataloginventory_stock_notification_template';

    public function __construct(
        Context $context,
        private readonly StateInterface $inlineTranslation,
        private readonly TransportBuilder $transportBuilder,
        private readonly Emulation $emulation,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($context);
    }

    public function sendEmail(string $html, int $count): void
    {
        if ($count <= 0) {
            return;
        }

        $storeId = 0;
        $recipientEmail = (string) $this->scopeConfig->getValue(self::CONFIG_PATH_RECIPIENT, ScopeInterface::SCOPE_STORE, $storeId);
        if ($recipientEmail === '') {
            return;
        }

        $sender = [
            'name' => (string) $this->scopeConfig->getValue('trans_email/ident_support/name', ScopeInterface::SCOPE_STORE, $storeId),
            'email' => (string) $this->scopeConfig->getValue('trans_email/ident_support/email', ScopeInterface::SCOPE_STORE, $storeId)
        ];

        $templateId = (string) $this->scopeConfig->getValue(self::CONFIG_PATH_TEMPLATE, ScopeInterface::SCOPE_STORE, $storeId);
        if ($templateId === '') {
            $templateId = self::DEFAULT_TEMPLATE_ID;
        }

        $initialEnvironmentInfo = null;
        try {
            $initialEnvironmentInfo = $this->emulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);
            $this->inlineTranslation->suspend();

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $storeId
                    ]
                )
                ->setTemplateVars(
                    [
                        'html' => $html,
                        'count' => $count
                    ]
                )
                ->setFrom($sender)
                ->addTo($recipientEmail)
                ->getTransport();

            $transport->sendMessage();
        } catch (LocalizedException $exception) {
            $this->logger->error('Low stock notification email failed: ' . $exception->getMessage());
        } finally {
            $this->inlineTranslation->resume();
            if ($initialEnvironmentInfo !== null) {
                $this->emulation->stopEnvironmentEmulation($initialEnvironmentInfo);
            }
        }
    }
}
