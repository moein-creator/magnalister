<?php



namespace Redgecko\Magnalister\Controller\Adminhtml\Backend;

use ML;
use MLFilesystem;
use MLSetting;

class Cache extends \Magento\Backend\App\Action
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
        $result = 'No file selected';
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

        $sFileName = $this->getRequest()->getParam('fileName');
        if (!empty($sFileName)) {
            if (substr($sFileName, -3) == 'json') {
                header('Content-Type: application/json');
                $sFilePath = MLFilesystem::getCachePath() . $sFileName;
            } else {
                header('Content-Type: application/gzip');
                $sFilePath = MLFilesystem::getLogPath() . $sFileName;

            }
            if (file_exists($sFilePath)) {
                header('Content-Disposition: attachment; filename=' . $sFileName);
                $result = file_get_contents($sFilePath);
            }
        }

        echo $result;
        die();

    }

    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Redgecko_Magnalister::menu');
    }

}
