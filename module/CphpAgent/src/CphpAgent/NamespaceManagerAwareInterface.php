<?php
/**
 * NamespaceManagerAwareInterface.php
 *
 * @date        09/03/14
 * @file        NamespaceManagerAwareInterface.php
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */

namespace CphpAgent;

/**
 * Class        NamespaceManagerAwareInterface
 *
 * @package     CphpAgent
 * @author      Frederic Dewinne <frederic@continuousphp.com>
 * @copyright   Copyright (c) continuousphp - All rights reserved
 * @license     http://opensource.org/licenses/BSD-3-Clause
 */
interface NamespaceManagerAwareInterface
{
    /**
     * @param array $namespaces
     *
     * @return $this
     */
    public function setNamespaces($namespaces);

    /**
     * @param string $namespace
     *
     * @return $this
     */
    public function addNamespace($namespace);

    /**
     * @return array
     */
    public function getNamespaces();
}