<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $tarPath = $config['destPath'] . '/deploy.tar';
        file_put_contents($tarPath . '.gz', file_get_contents($config['serverUrl']));
        try {
            $phar = new \PharData($tarPath . '.gz');
            $phar->decompress();
            $phar = new \PharData($tarPath);
            $phar->extractTo($config['destPath'], null, true);
        } catch (\Exception $e) {

        }
        return new ViewModel(array());
    }
}
