<?php
/**
 * RecursiveDirectoryAggregate.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      RecursiveDirectoryAggregate.php
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
class RecursiveDirectoryAggregate implements \IteratorAggregate
{
    /**
     * @var \Iterator
     */
    protected $iterator;

    /**
     * @param \SplFileInfo $file
     */
    public function __construct(\SplFileInfo $file)
    {
        if (! $file->isDir()) {
            throw new \InvalidArgumentException('Expecting directory');
        }
        $this->iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(
            $file->getPathname(),
            \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS
        ));
    }

    /**
     * @return \Iterator|\RecursiveIteratorIterator
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}
