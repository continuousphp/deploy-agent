<?php
/**
 * TaskRunnerManager.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      TaskRunnerManager.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Task\Runner;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\Stdlib\Exception\RuntimeException;

/**
 * TaskRunnerManager
 *
 * @package    Continuous\DeployAgent
 * @subpackage Task
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class TaskRunnerManager extends AbstractPluginManager
{
    /**
     * Whether or not to share by default
     *
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * Default aliases
     *
     * @var array
     */
    protected $aliases = [
    ];

    /**
     * Default set of adapters
     *
     * @var array
     */
    protected $invokableClasses = [
        'command' => 'Continuous\DeployAgent\Task\Runner\Command',
    ];

    /**
     * Default factory-based adapters
     *
     * @var array
     */
    protected $factories = [
    ];

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof TaskRunnerInterface) {
            // we're okay
            return;
        }

        throw new RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement Continuous\DeployAgent\Task\Runner\TaskRunnerInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}