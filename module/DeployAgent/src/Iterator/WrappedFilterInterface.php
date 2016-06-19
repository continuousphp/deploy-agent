<?php
/**
 * WrappedFilterInterface.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      WrappedFilterInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Iterator;

/**
 * WrappedFilterInterface
 *
 * @package    Continuous\DeployAgent
 * @subpackage Iterator
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface WrappedFilterInterface
{
    /**
     * @param \Iterator $iterator
     * @return \FilterIterator
     */
    public function getFilterIterator(\Iterator $iterator);

    /**
     * @return string
     */
    public function getIteratorName();
}