<?php
/**
 * AbstractProvider.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      AbstractProvider.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Provider;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbstractProvider
 *
 * @package    Continuous\DeployAgent
 * @subpackage Provider
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 *
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "continuousphp": "Continuousphp"
 * })
 */
abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
