<?php

namespace Redgecko\Magnalister\Controller\Adminhtml\Backend;

use ML;
use MLSetting;
use MLDatabase;
use MLFilesystem;

class Order extends \Magento\Backend\App\Action
{
    protected $request;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->request = $request;
        parent::__construct($context);
    }

    public function execute() {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $dir = $objectManager->get('Magento\Framework\Module\Dir');
        /**  @var $dir \Magento\Framework\Module\Dir */
        $appPath = $dir->getDir('Redgecko_Magnalister');
        if (file_exists($appPath . '/../MagnalisterLibrary/Core/ML.php')) {
            $_PluginPath = $appPath . '/../MagnalisterLibrary/Core/ML.php';
        }elseif (file_exists($appPath . '/../magnalisterlibrary/Core/ML.php')) {
            $_PluginPath = $appPath . '/../magnalisterlibrary/Core/ML.php';
        }

        if (file_exists($_PluginPath)) {
            require_once($_PluginPath);
            $output = ML::gi()->run();
            $sClientVersion = MLSetting::gi()->get('sClientBuild');
            $MLbodyClass = implode(' ', MLSetting::gi()->get('aBodyClasses'));
        }

        $orderId = $this->getRequest()->getPost('orderId');
        $platform = $this->getRequest()->getPost('platform');

        if ($platform == 'amazon') {
            $shipMethod = $this->getRequest()->getPost('shipMethod');
            $carrier = $this->getRequest()->getPost('carrierCode');

            if ($carrier !== '' || $shipMethod !== '') {
                $updateData = json_encode(['carrierCode' => $carrier, 'shipMethod' => $shipMethod]);
            } else {
                $updateData = null;
            }
        }

        if ($platform === 'otto') {
            $returnCarrier = $this->getRequest()->getPost('returnCarrier');
            $returnTrackingNumber = $this->getRequest()->getPost('returnTrackingNumber');

            if ($returnCarrier !== '' || $returnTrackingNumber !== '') {
                $updateData = json_encode(['returnCarrier' => $returnCarrier, 'returnTrackingNumber' => $returnTrackingNumber]);
            } else {
                $updateData = null;
            }
        }
        
        MLDatabase::getDbInstance()->update('magnalister_orders',
            array('shopAdditionalOrderField' => $updateData),
            array('orders_id' => $orderId)
        );

        die();
    }

    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Redgecko_Magnalister::menu');
    }
}
