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
use PhpAmqpLib\Connection\AMQPConnection;

class RabbitMqProvider extends Provider implements ProviderInterface
{

    protected $name = 'rabbit.mq';

    public function register(Application $app, array $options = array())
    {
        $config = $app->getConfig($this->name);
        $server = $config['server'];
        
        if (empty($server)) {
            return false;
        }

        $connection = new AMQPConnection($server['host'], $server['port'], $server['user'], $server['password']);
        
        return $connection;
    }

}