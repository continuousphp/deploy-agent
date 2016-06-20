<?php
/**
 * ProviderInterface.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ProviderInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Provider;

use Continuous\DeployAgent\Application\Application;
use Continuous\DeployAgent\Source\SourceInterface;

/**
 * ProviderInterface
 *
 * @package    Continuous\DeployAgent
 * @subpackage Provider
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface ProviderInterface
{
    /**
     * @param string $revision
     * @return SourceInterface
     */
    public function getSource($revision);

    /**
     * @return array
     */
    public function getRevisions();

    /**
     * @return Application
     */
    public function getApplication();

    /**
     * @param mixed $application
     * @return AbstractProvider
     */
    public function setApplication(Application $application);
}
