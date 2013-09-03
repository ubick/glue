<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue\Provider;

use Glue\Application;
use Glue\Provider;
use Glue\ProviderInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class DoctrineOrmProvider extends Provider implements ProviderInterface
{

    protected $name = 'doctrine.orm';

    public function register(Application $app, array $options = array())
    {
        if (empty($options['entity.path'])) {
            $options['entity.path'] = array();
        }

        if (!is_array($options['entity.path'])) {
            $options['entity.path'] = array($options['entity.path']);
        }

//        $config = Setup::createAnnotationMetadataConfiguration($options['entity.path'], $app->getEnvironment() == 'dev');
        $config = Setup::createConfiguration($app->getEnvironment() == 'dev');
        $driver = new AnnotationDriver(new AnnotationReader(), $options['entity.path']);

        AnnotationRegistry::registerLoader('class_exists');
        $config->setMetadataDriverImpl($driver);

//        $em = EntityManager::create($connectionParams, $config);

        $em = EntityManager::create($options, $config);

        return $em;
    }

}