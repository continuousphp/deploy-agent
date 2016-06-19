<?php
/**
 * Http.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Http.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent\Resource\Http;

use Continuous\DeployAgent\Event\DeployEvent;
use Continuous\DeployAgent\Source\SourceInterface;
use Continuous\DeployAgent\Workspace\Workspace;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Http\Request;

/**
 * Http
 *
 * @package    Continuous\DeployAgent
 * @subpackage Resource
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Http implements SourceInterface, EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * @var Client
     */
    protected $client;
    
    protected $filename;

    /**
     * @param mixed $filename
     * @return Http
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @param null $path
     */
    public function __construct($path = null)
    {
        if (null !== $path) {
            $this->setPathname($path);
        }
    }

    /**
     * @return Workspace
     */
    public function fetch()
    {
        $events = $this->getEventManager();
        $event  = new DeployEvent(null, $this);
        $events->trigger(DeployEvent::EVENT_FETCH_PRE, $event);

        // Check for filename, if any
        $response = $this->client
            ->setMethod(Request::METHOD_HEAD)
            ->send();

        $destination = $this->getStreamPath($response->getHeaders());
        // retrieve file
        $this->client
            ->setMethod(Request::METHOD_GET)
            ->setStream($destination->getPathname())
            ->send();

        // create build file
        $workspace = new Workspace($destination->getPathname());
        $event->setWorkspace($workspace);
        $events->trigger(DeployEvent::EVENT_FETCH_POST, $event);

        return $workspace;
    }

    /**
     * @param Headers $headers
     *
     * @return \SplFileInfo
     */
    public function getStreamPath(Headers $headers)
    {
        $filename  = null;
        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid();
        if (! file_exists($directory)) {
            mkdir($directory, 0775, true);
        }

        if (!$this->filename) {
            if ($headers->has('content-disposition')) {
                $field = $headers->get('content-disposition')->getFieldValue();
                if (preg_match('`filename\="(.*)"`', $field, $matches)) {
                    $this->filename = basename($matches[1]);
                }
            }
            if (null === $filename) {
                $this->filename = uniqid();
            }
        }

        return new \SplFileInfo($directory . DIRECTORY_SEPARATOR . $this->filename);
    }

    /**
     * @param $path
     */
    public function setPathname($path)
    {
        $this->client = new Client($path);
    }
}