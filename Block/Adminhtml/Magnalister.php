<?php
namespace Redgecko\Magnalister\Block\Adminhtml;

use ML;
use MLFilesystem;
use MLHttp;
use MLOrder;
use MLSetting;


class Magnalister extends \Magento\Backend\Block\Template
{


    /**
     * @return string
     */
    public function DisplayMagnalister()
    {


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
        /**  @var $productMetadata Magento\Framework\App\ProductMetadataInterface */
        $sMagentoReversion = $productMetadata->getEdition();

        define("MLMAGNETOREVERSION", $productMetadata->getEdition());
        define("MLMAGENTOVERSION", $productMetadata->getVersion());

        $dir = $objectManager->get('Magento\Framework\Module\Dir');
        /**  @var $dir \Magento\Framework\Module\Dir */


        $appPath = $dir->getDir('Redgecko_Magnalister');
        if (file_exists($appPath . '/../MagnalisterLibrary/Core/ML.php')) {
            $_PluginPath = $appPath . '/../MagnalisterLibrary/Core/ML.php';
        }elseif (file_exists($appPath . '/../magnalisterlibrary/Core/ML.php')) {
            $_PluginPath = $appPath . '/../magnalisterlibrary/Core/ML.php';
        }
        $debugPrint = '';
        if (file_exists($_PluginPath)) {
            require_once($_PluginPath);
            $output = ML::gi()->run();
            /* @var Mage_Adminhtml_Block_Page_Head $oHead */
         //   $oHead=$this->getLayout()->getBlock('head');
            $sClientVersion = MLSetting::gi()->get('sClientBuild');
            $MLjs = '';


            $sMainUrl = MLHttp::gi()->getBaseUrl();




        }

        return ' ' . $output . '<pre>' . $debugPrint . '</pre>';
    }
}
