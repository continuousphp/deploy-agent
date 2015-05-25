<?php
/**
 * ApplicationController.php
 *
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ApplicationController.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Controller;

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
    
    public function addAction()
    {
        $providers = [
            "continuousphp"
        ];
        
//        $providerKey = Select::prompt(
//            "Select a provider:",
//            $providers
//        );
        // predefined the provider to continuousphp as it's currently the only one supported
        $providerKey = 0;
        
        /** @var \Continuous\DeployAgent\Provider\Continuousphp $provider */
        $provider = $this->getServiceLocator()
            ->get('provider/' . $providers[$providerKey]);
        /** @var \Continuous\DeployAgent\Application\Application $application */
        $application = $this->getServiceLocator()
            ->get('application/application');
        $application->setProvider($provider);
        
        $token = Line::prompt("Enter a valid continuousphp access token: ");
        $provider->setToken($token);
        
        $this->getConsole()->writeLine('Querying API for your projects...', ColorInterface::LIGHT_CYAN);
        
        $projects = $provider->getProjects();
        
        $projectOptions = [];
        
        foreach($projects as $entry) {
            $projectOptions[]= $entry['_embedded']['provider']['uniqueIdentifier'] . '/' . $entry['url'];
        }
        
        $projectKey = Select::prompt("Select a project:", $projectOptions, false, true);
        
        $project = $projects[$projectKey];
        $provider->setProject($project);

        $this->getConsole()->writeLine('Querying API for your project pipelines...', ColorInterface::LIGHT_CYAN);
        
        $referenceOptions = $provider->getReferences();
        
        $referenceKey = Select::prompt("Select a pipeline:", $referenceOptions, false, true);
        
        $provider->setReference($referenceOptions[$referenceKey]);
        
        $name = Line::prompt("Enter an application name: ");
        
        return false;
    }
}
