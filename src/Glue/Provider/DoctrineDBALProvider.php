<?php

/**
 *
 * PHP version  5.4
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue\Provider;

use Glue\Application;
use Glue\Provider;
use Glue\ProviderInterface;
use Doctrine\DBAL;

class DoctrineDBALProvider extends Provider implements ProviderInterface
{

    protected $name = 'doctrine.dbal';

    public function register(Application $app, array $options = array())
    {
        if (!$options) {
            return false;
        }
        
        $dbalConfig = new DBAL\Configuration();
        $dbal = DBAL\DriverManager::getConnection($options, $dbalConfig);

        return $dbal;
    }

}