<?php

namespace Redgecko\Magnalister\Block\Adminhtml\Order\View;

use ML;
use MLOrder;
use MLFilesystem;

class View extends \Magento\Backend\Block\Template
{
    public function getMagnalisterOrder()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $dir = $objectManager->get('Magento\Framework\Module\Dir');
        $appPath = $dir->getDir('Redgecko_Magnalister');
        if (file_exists($appPath . '/../MagnalisterLibrary/Core/ML.php')) {
            $_PluginPath = $appPath . '/../MagnalisterLibrary/Core/ML.php';
        }elseif (file_exists($appPath . '/../magnalisterlibrary/Core/ML.php')) {
            $_PluginPath = $appPath . '/../magnalisterlibrary/Core/ML.php';
        }
        require_once($_PluginPath);
        $_order = $this->getRequest()->getParam('order_id');
        ML::setFastLoad(true);
        return MLOrder::factory()->set('current_orders_id', $_order);
    }

    public function marketplaceOrderLogo()
    {
        $oOrder = $this->getMagnalisterOrder();
        return $oOrder->getLogo();
    }

    public function magnalisterOrderData()
    {
        $oOrder = $this->getMagnalisterOrder();
        if ($oOrder->get('special') !== null){
            include MLFilesystem::gi()->getViewPath('hook_orderdetails');
        }
    }
}
