<?php
namespace Redgecko\Magnalister\Controller\Adminhtml\Iframe;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Result\PageFactory;
use ML;
use MLHttp;
use MLSetting;

class Index extends \Magento\Backend\App\Action {
    protected $_publicActions = ['index'];
    protected $resultPageFactory = false;

    public function __construct(
        Context     $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->prepareWritablePaths();
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute() {
        echo $this->DisplayMagnalister();
    }

    /**
     * @return string
     */
    public function DisplayMagnalister() {
        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
            /**  @var $productMetadata Magento\Framework\App\ProductMetadataInterface */
            $sMagentoReversion = $productMetadata->getEdition();

            define("MLMAGNETOREVERSION", $productMetadata->getEdition());
            define("MLMAGENTOVERSION", $productMetadata->getVersion());

            $dir = $objectManager->get('Magento\Framework\Module\Dir');
            /**  @var $dir \Magento\Framework\Module\Dir */
            $appPath = $dir->getDir('Redgecko_Magnalister');
            if (file_exists($appPath.'/../magnalisterlibrary/Core/ML.php')) {
                $_PluginPath = $appPath.'/../magnalisterlibrary/Core/ML.php';
            }
            $debugPrint = '';
            if (file_exists($_PluginPath)) {
                require_once($_PluginPath);
                $output = ML::gi()->run();
                $sClientVersion = MLSetting::gi()->get('sClientBuild');
                $MLcss = '';
                $MLjs = '';
                foreach (array_unique(MLSetting::gi()->get('aCss')) as $sFile) {//echo $sFile;
                    $MLcss .= '
                    <link rel="stylesheet" type="text/css" href="'.sprintf(MLHttp::gi()->getResourceUrl('css_'.$sFile), $sClientVersion).'">';
                }

                foreach (array_unique(MLSetting::gi()->get('aJs')) as $sFile) {
                    $MLjs .= '
                    <script src="'.sprintf(MLHttp::gi()->getResourceUrl('js_'.$sFile), $sClientVersion).'" type="text/javascript"></script>';
                }
                $MLbodyClass = implode(' ', MLSetting::gi()->get('aBodyClasses'));

            }
            header('Content-Type: text/html; charset=utf-8');
        } catch (\Throwable $ex) {
            return ('<!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <title>magnalister exception</title>
                </head>
                <body>'.(isset(ObjectManager::getInstance()->get('\Magento\Framework\App\RequestInterface')->getParams()['ml-debug']) ?
                    $ex->getMessage().'<br>'.
                    $ex->getFile().'<br>'.
                    $ex->getLine().'<pre>'.
                    $ex->getTraceAsString().'</pre>' :
                    'An error occurs, please contact <a href="http://www.magnalister.com/kontakt" class="ml-js-noBlockUi" target="_blank">magnalister-Service</a>
                ').'</body>
            </html>
        ');
        }


        return ' <!DOCTYPE html>            <html>                <head>                    <meta charset="utf-8">                    <title>magnalister Admin</title>                                       '.$MLcss.'                    '.$MLjs.'                </head>                <body class="'.$MLbodyClass.'">                    '.$output.'                    <pre>'.$debugPrint.'</pre>                 </body>            </html>        ';

        //return ' ' . $output . '<pre>' . $debugPrint . '</pre>';
    }

    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Redgecko_Magnalister::menu');
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
