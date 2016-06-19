<?php
/**
 * IteratorAwareInterface.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      IteratorAwareInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Iterator;

/**
 * IteratorAwareInterface
 *
 * @package    Continuous\DeployAgent
 * @subpackage Iterator
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface IteratorAwareInterface
{
    /**
     * @param \Iterator $iterator
     *
     * @return $this
     */
    public function setIterator(\Iterator $iterator);
}
