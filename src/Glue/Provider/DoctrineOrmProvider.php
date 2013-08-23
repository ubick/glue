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

class DoctrineOrmProvider extends Provider implements ProviderInterface
{

    protected $name = 'doctrine.orm';

    public function register(Application $app, array $options = array())
    {
        if (empty($options['entity.path'])) {
            $options['entity.path'] = array();
        }
        
        $config = Setup::createAnnotationMetadataConfiguration($options['entity.path'], $app->getEnvironment() == 'dev');
        $em = EntityManager::create($options, $config);

        return $em;
    }

}