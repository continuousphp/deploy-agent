<?php
/**
 * CallbackFilter.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      CallbackFilter.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Iterator;

/**
 * CallbackFilter
 *
 * @package    Continuous\DeployAgent
 * @subpackage Iterator
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class CallbackFilter implements WrappedFilterInterface
{
    /**
     * @var string
     */
    protected $iteratorClass = 'CallbackFilterIterator';

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callback = $callable;
    }

    /**
     * @param \Iterator $iterator
     * @return mixed
     */
    public function getFilterIterator(\Iterator $iterator)
    {
        $filter = $this->iteratorClass;
        return new $filter($iterator, $this->callback);
    }

    /**
     * @return string
     */
    public function getIteratorName()
    {
        return $this->iteratorClass;
    }
}