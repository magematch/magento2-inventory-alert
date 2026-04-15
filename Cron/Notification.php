<?php
declare(strict_types=1);

namespace Rameera\LowStockNotification\Cron;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Data\Collection as DataCollection;
use Magento\Framework\Exception\NoSuchEntityException;
use Rameera\LowStockNotification\Model\LowStockCollectionFactory;
use Rameera\LowStockNotification\Helper\Email;

class Notification
{
    private const CONFIG_PATH_ENABLED = 'cataloginventory/stock_notification/status';

    public function __construct(
        private readonly LowStockCollectionFactory $lowstocksFactory,
        private readonly Email $emailHelper,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        private readonly \Magento\Framework\Escaper $escaper
    ) {
    }

    public function execute(): void
    {
        $isEnabled = (bool) $this->scopeConfig->getValue(self::CONFIG_PATH_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$isEnabled) {
            return;
        }

        $collection = $this->lowstocksFactory->create()
            ->addAttributeToSelect('*')
            ->filterByIsQtyProductTypes()
            ->joinInventoryItem('qty')
            ->useManageStockFilter(0)
            ->useNotifyStockQtyFilter(0)
            ->setOrder('qty', DataCollection::SORT_ORDER_ASC);

        $totalCount = $collection->count();
        if ($totalCount === 0) {
            return;
        }

        $html = '<table style="border: 1px solid #CACACA; border-collapse: collapse;">';
        $html .= '<tr>';
        $html .= '<th style="border: 1px solid #CACACA; padding: 10px;">Name</th>';
        $html .= '<th style="border: 1px solid #CACACA; padding: 10px;">SKU</th>';
        $html .= '<th style="border: 1px solid #CACACA; padding: 10px;">Qty</th>';
        $html .= '</tr>';

        foreach ($collection as $item) {
            $productName = '';
            try {
                $productName = (string) $this->productRepository->getById((int) $item->getId())->getName();
            } catch (NoSuchEntityException) {
                continue;
            }

            $html .= '<tr>';
            $html .= '<td style="border: 1px solid #CACACA; padding: 10px;">' . $this->escaper->escapeHtml($productName) . '</td>';
            $html .= '<td style="border: 1px solid #CACACA; padding: 10px;">' . $this->escaper->escapeHtml((string) $item->getSku()) . '</td>';
            $html .= '<td style="border: 1px solid #CACACA; padding: 10px;">' . $this->escaper->escapeHtml((string) $item->getQty()) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        $this->emailHelper->sendEmail($html, $totalCount);
    }
}
