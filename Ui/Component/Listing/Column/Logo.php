<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Redgecko\Magnalister\Ui\Component\Listing\Column;

use ML;
use MLHttp;
use MLOrder;
use MLMagento2Alias;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Logo
 */
class Logo extends Column
{
    protected $storeManager;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ContextInterface $context,
        StoreManagerInterface $storeManager,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if($item[$this->getData('name')] != '') {
                    $item[$this->getData('name') . '_src'] = $this->getOrderLogo($item['entity_id']);
                    $item[$this->getData('name') . '_alt'] = $item[$this->getData('name')].'_orderview';
                    $item[$this->getData('name') . '_link'] = $this->getOrderLogo($item['entity_id']);
                    $item[$this->getData('name') . '_orig_src'] = $this->getOrderLogo($item['entity_id']);
                } else {
                    $item[$this->getData('name')] = false;
                }
            }
        }

        return $dataSource;
    }

    private function getOrderLogo(string $OrderId) {
        $_PluginPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'magnalisterlibrary' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'ML.php';

        if (!file_exists($_PluginPath) && file_exists(dirname(__FILE__) . '/../../../../../magnalisterlibrary/Core/ML.php')) {
            $_PluginPath = dirname(__FILE__) . '/../../../../../magnalisterlibrary/Core/ML.php';
        }

        require_once $_PluginPath;

        MLOrder::factory()->set('current_orders_id', $OrderId);
        if (!ML::isInstalled()) {
            throw new Exception('magnalister is not installed');
        }

        $connection = MLMagento2Alias::getMagentoObjectManager()
            ->create(\Magento\Framework\App\ResourceConnection::class)
            ->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $tblSalesOrder = $connection->getTableName('sales_order_status_state');
        $hasTransactionId = $connection->fetchRow('SELECT COUNT(*) FROM `magnalister_orders` WHERE `current_orders_id` ="'.$OrderId.'"');

        if (!empty($hasTransactionId)) {
            ML::setFastLoad(true);
            $oOrder = MLOrder::factory()->set('current_orders_id', $OrderId);
            $sTitleHtml = $oOrder->getLogo();
        }

        return $sTitleHtml;
    }
}
