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

use Continuous\DeployAgent\Application\Application;
use Continuous\DeployAgent\Task\TaskManager;
use League\Flysystem\Filesystem;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * HookController
 *
 * @package    Continuous\DeployAgent
 * @subpackage Controller
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class HookController extends AbstractActionController
{
    public function deployAction()
    {
        $build = $this->params()->fromPost('build_id');

        /** @var \Continuous\DeployAgent\Application\ApplicationManager $applicationManager */
        $applicationManager = $this->getServiceLocator()
            ->get('application/application-manager');

        /** @var Application $application */
        $application = $applicationManager->find(
            $this->params('buildProvider'),
            $this->params()->fromPost('provider'),
            $this->params()->fromPost('repository'),
            $this->params()->fromPost('pipeline')
        );

        $taskManager = new TaskManager();
        $taskManager->setPackageStoragePath($this->getServiceLocator()->get('Config')['agent']['package_storage_path'])
            ->setPackageFileSystem(
                new Filesystem($this->getServiceLocator()->get('BsbFlysystemAdapterManager')->get('packages'))
            );

        $application->getEventManager()->attachAggregate($taskManager);

        $application->getEventManager()->trigger(Application::EVENT_INSTALL, $application, ['build' => $build]);

        return false;
    }
}
