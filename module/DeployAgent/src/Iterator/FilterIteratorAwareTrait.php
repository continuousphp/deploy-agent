<?php
/**
 * FilterIteratorAwareTrait.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      FilterIteratorAwareTrait.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Iterator;

use Traversable;

/**
 * FilterIteratorAwareTrait
 *
 * @package    Continuous\DeployAgent
 * @subpackage Iterator
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
trait FilterIteratorAwareTrait
{
    /**
     * @var WrappedFilterInterface[]
     */
    protected $iteratorFilters = [];

    /**
     * @var array|Traversable $filters
     * @return $this
     */
    public function setFilters($filters)
    {
        if (! is_array($filters) && ! $filters instanceof Traversable) {
            throw new \InvalidArgumentException('Expecting a Traversable element');
        }
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
        return $this;
    }

    /**
     * @param $iterator
     * @return $this
     */
    public function addFilter($iterator)
    {
        if (is_callable($iterator)) {
            $iterator = new CallbackFilter($iterator);
        }
        if (! $iterator instanceof WrappedFilterInterface) {
            throw new \InvalidArgumentException('Expecting a WrappedFilterInterface class');
        }
        if (! is_subclass_of($iterator->getIteratorName(), 'FilterIterator')) {
            throw new \InvalidArgumentException('Expecting a FilterIterator class');
        }
        $this->iteratorFilters[] = $iterator;

        return $this;
    }

    /**
     * @param $iterator
     * @return \FilterIterator|\SplFileInfo[]
     */
    protected function getFilterIterator($iterator)
    {
        foreach ($this->iteratorFilters as $filter) {
            $iterator = $filter->getFilterIterator($iterator);
        }
        return $iterator;
    }
}
