<?php
/**
 * IndexController.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      IndexController.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * IndexController
 *
 * @package    Continuous\DeployAgent
 * @subpackage Controller
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        if (class_exists('\ZF\Apigility\Admin\Module', false)) {
            return $this->redirect()->toRoute('zf-apigility/ui');
        }
        
        return new ViewModel();
    }
}
