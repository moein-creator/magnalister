<?php
namespace Redgecko\Magnalister\Controller\Index;

use ML;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        $this->prepareWritablePaths();
        return parent::__construct($context);
    }

    public function execute()
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
        $oRequest = $objectManager->create(\Magento\Framework\App\RequestInterface::class);
        $debugPrint = '';
        if (file_exists($_PluginPath)) {
            require_once($_PluginPath);
            ML::gi()->runFrontend('do');
        }

        //return $this->_pageFactory->create();
    }

    private function prepareWritablePaths() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $directory = $objectManager->get('Magento\Framework\App\Filesystem\DirectoryList');

        $sWritablePath = $directory->getPath('cache').DIRECTORY_SEPARATOR.'magnalister'.DIRECTORY_SEPARATOR;
        $sLogPath = $directory->getPath('log').DIRECTORY_SEPARATOR.'RedMagnalisterMG2'.DIRECTORY_SEPARATOR;

        if (!is_dir($sWritablePath)) {
            mkdir($sWritablePath);
        }
        if (is_dir($sWritablePath)) {
            define('MAGNALISTER_WRITABLE_DIRECTORY', $sWritablePath);
        }

        if (!is_dir($sLogPath)) {
            mkdir($sLogPath);
        }

        if (is_dir($sLogPath)) {
            define('MAGNALISTER_LOG_DIRECTORY', $sLogPath);
        }
    }
}
