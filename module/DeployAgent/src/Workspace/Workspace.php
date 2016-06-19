<?php
/**
 * Workspace.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Workspace.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Workspace;

use Iterator;

/**
 * Workspace
 *
 * @package    Continuous\DeployAgent
 * @subpackage Workspace
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Workspace extends \SplFileInfo
{
    /**
     * @var Iterator
     */
    protected $iterator;

    /**
     * @param Iterator $iterator
     *
     * @return $this
     */
    public function setIterator(Iterator $iterator)
    {
        $this->iterator = $iterator;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->iterator ?: new \ArrayIterator;
    }
}
