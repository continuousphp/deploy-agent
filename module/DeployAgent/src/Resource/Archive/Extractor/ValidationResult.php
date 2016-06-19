<?php
/**
 * ValidationResult.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      ValidationResult.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Resource\Archive\Extractor;

/**
 * ValidationResult
 *
 * @package    Continuous\DeployAgent
 * @subpackage Resource
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class ValidationResult
{
    protected $valid = true;
    protected $message;

    /**
     * @param bool $valid
     *
     * @return self
     */
    public function setValid($valid)
    {
        $this->valid = (bool) $valid;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message  = (string) $message;
        $this->valid = false;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
