<?php
/**
 * EntityManagerInitializer.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      EntityManagerInitializer.php
 * @link      http://github.com/continuousphp/deploy-agent the canonical source repo
 */

namespace Continuous\DeployAgent;

use Doctrine\ORM\EntityManager;
use Reprovinci\DoctrineEncrypt\Subscribers\DoctrineEncryptSubscriber;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * EntityManagerInitializer
 *
 * @package    Continuous\DeployAgent
 * @license    http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class EntityManagerInitializer implements InitializerInterface
{
    /**
     * Initialize
     *
     * @param mixed                   $instance
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return void
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof EntityManagerAwareInterface) {
            /** @var EntityManager $entityManager */
            $entityManager = $serviceLocator->get('entity_manager');
            $instance->setEntityManager($entityManager);
        }
        
        if ($instance instanceof EntityManager) {
            $secret = pack("H*", $serviceLocator->get('config')['agent']['hash-key']);

            $subscriber = new DoctrineEncryptSubscriber(
                new \Doctrine\Common\Annotations\AnnotationReader,
                new \Reprovinci\DoctrineEncrypt\Encryptors\AES256Encryptor($secret)
            );

            $eventManager = $instance->getEventManager();
            $eventManager->addEventSubscriber($subscriber);
        }
    }
}
