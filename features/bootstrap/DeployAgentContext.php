<?php

namespace Continuous\Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Continuous\DeployAgent\Application\ApplicationManager;
use Continuous\DeployAgent\Application\Application as ApplicationEntity;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Application;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Defines application features from the specific context.
 */
class DeployAgentContext implements Context, SnippetAcceptingContext
{
    /**
     * @var Application
     */
    protected static $application;
    
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }
    
    /**
     * @BeforeSuite
     */
    public static function setupTests(BeforeSuiteScope $scope)
    {
        copy('config/autoload/db.test.php.dist', 'config/autoload/db.test.php');
        
        if (file_exists('data/db/test.db')) {
            unlink('data/db/test.db');
        }

        $appConfig = require 'config/application.config.php';

        if (file_exists('config/development.config.php')) {
            $appConfig = \Zend\Stdlib\ArrayUtils::merge($appConfig, require 'config/development.config.php');
        }
        
        self::$application = Application::init($appConfig);
    }
    
    /**
     * @AfterSuite
     */
    public static function removeDB(AfterSuiteScope $scope)
    {
        unlink('config/autoload/db.test.php');
    }
    
    /**
     * @BeforeScenario
     */
    public function provisionDB(BeforeScenarioScope $scope)
    {
        if (file_exists('data/db/test.db')) {
            echo 'removing db...';
            unlink('data/db/test.db');
        }
        exec('./agent orm:schema-tool:create');
    }

    /**
     * @AfterScenario
     */
    public function cleanDB(AfterScenarioScope $scope)
    {
        /** @var EntityManager $entityManager */
        $entityManager = self::$application->getServiceManager()->get('entitymanager');
        $entityManager->clear();
        $entityManager->getConnection()->close();
        unlink('data/db/test.db');
    }

    /**
     * Checks whether a file at provided path exists.
     *
     * @Given /^file "([^"]*)" should exist$/
     *
     * @param   string $path
     */
    public function fileShouldExist($path)
    {
        \PHPUnit_Framework_Assert::assertFileExists($path);
    }

    /**
     * @Then file :arg1 should match :arg2
     */
    public function fileShouldMatch($file, $expected)
    {
        \PHPUnit_Framework_Assert::assertFileEquals($expected, $file);
    }


    /**
     * @Given I have the application
     */
    public function createApplication(TableNode $table)
    {
        /** @var ApplicationManager $applicationManager */
        $applicationManager = self::$application->getServiceManager()->get('application/application-manager');

        $data = [];
        foreach ($table->getTable() as $row) {
            $data[$row[0]] = $row[1];
        }
        $data['reference'] = $data['pipeline'];
        
        // get provider instance from service manager
        $data['provider'] = self::$application->getServiceManager()->get('provider/' . $data['provider']);
        
        /** @var ApplicationEntity $application */
        $application = self::$application->getServiceManager()->get('application/application');
        /** @var ClassMethods $hydrator */
        $hydrator = self::$application->getServiceManager()->get('hydratormanager')->get('classmethods');
        
        $hydrator->hydrate($data, $data['provider']);
        
        $hydrator->hydrate($data, $application);
        
        $applicationManager->persist($application);
    }


    /**
     * @Then I should have the application
     */
    public function applicationExists(TableNode $table)
    {
        /** @var ApplicationManager $applicationManager */
        $applicationManager = self::$application->getServiceManager()->get('application/application-manager');
        
        $data = [];
        foreach ($table->getTable() as $row) {
            $data[$row[0]] = $row[1];
        }
        
        $application = $applicationManager->get($data['name']);
        \PHPUnit_Framework_Assert::assertInstanceOf(
            'Continuous\DeployAgent\Application\Application',
            $application
        );
        
        foreach($data as $property => $value) {
            switch($property) {
                case 'pipeline':
                    $property = 'reference';
                case 'token':
                case 'repositoryProvider':
                case 'repository':
                    \PHPUnit_Framework_Assert::assertAttributeEquals(
                        $value,
                        $property,
                        $application->getProvider()
                    );
                    break;
                case 'provider':
                    $provider = self::$application->getServiceManager()->get('provider/' . $value);
                    \PHPUnit_Framework_Assert::assertAttributeInstanceOf(
                        get_class($provider),
                        $property,
                        $application
                    );
                    break;
                default:
                    \PHPUnit_Framework_Assert::assertAttributeEquals(
                        $value,
                        $property,
                        $application
                    );
            }
        }
    }
}
