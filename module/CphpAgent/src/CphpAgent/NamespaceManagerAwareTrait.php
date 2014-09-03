<?php
/**
 * NamespaceManagerAwareTrait.php
 *
 * @date        09/03/14
 * @file        NamespaceManagerAwareTrait.php
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */

namespace CphpAgent;

/**
 * Class        NamespaceManagerAwareTrait
 *
 * @package     CphpAgent
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */
trait NamespaceManagerAwareTrait
{
    /**
     * @var array
     */
    protected $namespaces = [];

    /**
     * @param $namespaces
     *
     * @return $this
     */
    public function setNamespaces($namespaces)
    {
        $this->namespaces = (array) $namespaces;

        return $this;
    }

    /**
     * @param string $namespace
     *
     * @return $this
     */
    public function addNamespace($namespace)
    {
        $this->namespaces[] = (string) $namespace;

        return $this;
    }

    /**
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }
}