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

    public function register(Application $app)
    {
        $dbParams = $app->getConfig($this->name);

        if (!empty($dbParams)) {
            $paths = array($dbParams['entity.path']);
            $isDevMode = true;

            if ($app->getEnvironment() != 'dev') {
                $isDevMode = false;
            }

            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
            $em = EntityManager::create($dbParams, $config);

            return $em;
        }

        return false;
    }

}