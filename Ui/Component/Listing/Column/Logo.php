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
        if (file_exists(dirname(__FILE__) . '/../../../../../MagnalisterLibrary/Core/ML.php')) {
            $_PluginPath = dirname(__FILE__) . '/../../../../../MagnalisterLibrary/Core/ML.php';
        }elseif (file_exists(dirname(__FILE__) . '/../../../../../magnalisterlibrary/Core/ML.php')){
            $_PluginPath = dirname(__FILE__) . '/../../../../../magnalisterlibrary/Core/ML.php';
        }
        require_once $_PluginPath;
        if (!ML::isInstalled()) {
            throw new Exception('magnalister is not installed');
        }
        ML::setFastLoad(true);
        ML::gi();//to load MLMagento2Alias
        if (isset($dataSource['data']['items'])) {
            $sName = $this->getData('name');

            foreach ($dataSource['data']['items'] as &$item) {
                $oOrder = MLOrder::factory()->set('current_orders_id', $item['entity_id']);
                if($oOrder->exists()) {
                    $sLogo = $oOrder->getLogo();
                    $item[$sName . '_src'] = $sLogo;
                    $item[$sName . '_alt'] = $oOrder->get('platform').'_orderview';
                    $item[$sName . '_link'] = $sLogo;
                    $item[$sName . '_orig_src'] = $sLogo;
                } else {
                    $item[$sName] = false;
                }
            }
        }

        return $dataSource;
    }

}
