<?php
/**
 * ApplicationController.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ApplicationController.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Controller;

use Continuous\DeployAgent\Agent\Agent;
use Continuous\DeployAgent\Application\Application;
use Continuous\DeployAgent\Event\DeployEvent;
use Continuous\DeployAgent\Provider\Continuousphp;
use Continuous\DeployAgent\Resource\Archive\Archive;
use Continuous\DeployAgent\Resource\FileSystem\Directory;
use Continuous\DeployAgent\Task\TaskManager;
use League\Flysystem\Filesystem;
use Zend\Config\Config;
use Zend\Console\ColorInterface;
use Zend\Console\Console;
use Zend\Console\Prompt\Line;
use Zend\Console\Prompt\Select;
use Zend\Mvc\Controller\AbstractConsoleController;
use Zend\View\Model\ConsoleModel;

/**
 * ApplicationController
 *
 * @package    Continuous\DeployAgent
 * @subpackage Controller
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class ApplicationController extends AbstractConsoleController
{
    public function listAction()
    {
        $model = new ConsoleModel();
        
        $model->setResult('No application found' . PHP_EOL);
        
        return $model;
    }
    
    public function deployAction()
    {
        /** @var \Zend\Console\Request $request */
        $request = $this->getRequest();
        
        // name param
        if (!$name = $request->getParam('name')) {
            $name = Line::prompt("Enter an application name: ");
        }
        
        $build = $request->getParam('build');

        /** @var \Continuous\DeployAgent\Application\ApplicationManager $applicationManager */
        $applicationManager = $this->getServiceLocator()
            ->get('application/application-manager');
        
        /** @var Application $application */
        $application = $applicationManager->get($name);
        
        $taskManager = new TaskManager();
        $taskManager->setConsole($this->getConsole())
                    ->setPackageStoragePath($this->getServiceLocator()->get('Config')['agent']['package_storage_path'])
                    ->setPackageFileSystem(
                        new Filesystem($this->getServiceLocator()->get('BsbFlysystemAdapterManager')->get('packages'))
                    );

        $application->getEventManager()->attachAggregate($taskManager);
        
        $application->getEventManager()->trigger(Application::EVENT_INSTALL, $application, ['build' => $build]);

        return false;
    }
    
    public function addAction()
    {
        /** @var \Zend\Console\Request $request */
        $request = $this->getRequest();

        /** @var \Continuous\DeployAgent\Application\Application $application */
        $application = $this->getServiceLocator()
            ->get('application/application');
        
        // provider param
        if ($request->getParam('provider')) {
            /** @var \Continuous\DeployAgent\Provider\Continuousphp $provider */
            $provider = $this->getServiceLocator()
                ->get('provider/' . $request->getParam('provider'));
        } else {
            $providers = [
                "continuousphp"
            ];
            
            $providerKey = Select::prompt(
                "Select a provider:",
                $providers
            );
            
            /** @var \Continuous\DeployAgent\Provider\Continuousphp $provider */
            $provider = $this->getServiceLocator()
                ->get('provider/' . $providers[$providerKey]);
        }
        $application->setProvider($provider);
        
        // token param
        if (!$token = $request->getParam('token')) {
            $token = Line::prompt("Enter a valid continuousphp access token: ");
        }
        $provider->setToken($token);
        
        // project param
        if ($request->getParam('repository-provider') && $request->getParam('repository')) {
            $provider->setRepositoryProvider($request->getParam('repository-provider'))
                ->setRepository($request->getParam('repository'));
        } else {
            $this->getConsole()->writeLine('Querying API for your projects...', ColorInterface::LIGHT_CYAN);
            
            $projects = $provider->getProjects();
            
            $projectOptions = [];
            
            foreach ($projects as $entry) {
                $projectOptions[]= $entry['_embedded']['provider']['uniqueIdentifier'] . '/' . $entry['url'];
            }
            
            if ($projectOptions) {
                $projectKey = Select::prompt("Select a project:", $projectOptions, false, true);
                
                $project = $projects[$projectKey];
                $provider->setProject($project);
            } else {
                $this->getConsole()->writeLine(
                    'There is no project to setup in your continuousphp account',
                    ColorInterface::LIGHT_RED
                );
                return false;
            }
        }

        // pipeline param
        if (!$reference = $request->getParam('pipeline')) {
            $this->getConsole()->writeLine('Querying API for your project pipelines...', ColorInterface::LIGHT_CYAN);
            
            $referenceOptions = $provider->getReferences();
            
            $referenceKey = Select::prompt("Select a pipeline:", $referenceOptions, false, true);
            $reference = $referenceOptions[$referenceKey];
        }
        $provider->setReference($reference);
        
        // name param
        if (!$name = $request->getParam('name')) {
            $name = Line::prompt("Enter an application name: ");
        }
        $application->setName($name);
        
        // destination param
        if (!$path = $request->getParam('path')) {
            $path = Line::prompt("Enter the application path: ");
        }
        $application->setPath($path);
        
        /** @var \Continuous\DeployAgent\Application\ApplicationManager $applicationManager */
        $applicationManager = $this->getServiceLocator()
            ->get('application/application-manager');
        
        $applicationManager->persist($application);
        
        return false;
    }
}
