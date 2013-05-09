<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Core\Provider;

use Core\Application;
use Core\Provider;
use Core\ProviderInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MonologProvider extends Provider implements ProviderInterface
{

    protected $name = 'monolog';

    public function register(Application $app)
    {
        $config = $app->getConfig($this->name);

        if (empty($config)) {
            return false;
        }

        $logger = new Logger('rabbitmq');
        $handler = new StreamHandler(__DIR__ . '/../../../' . $config['path'], Logger::INFO);
        $logger->pushHandler($handler);
        
        return $logger;
    }

}