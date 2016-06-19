<?php
/**
 * ExtractorInterface.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ExtractorInterface.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Resource\Archive\Extractor;

/**
 * ExtractorInterface
 *
 * @package    Continuous\DeployAgent
 * @subpackage Resource
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface ExtractorInterface
{
    /**
     * Check if the file must be accepted, or not
     *
     * @param \SplFileInfo $archive
     * @return ValidationResult
     */
    public function validate(\SplFileInfo $archive);

    /**
     * @param \SplFileInfo $archive
     * @return bool
     */
    public function extract(\SplFileInfo $archive, \SplFileInfo $destination);
}
