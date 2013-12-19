<?php

/*
 *
 * (c) Liviu Panainte <liviu.panainte@gmail.com>
 *
 */

namespace Glue\Provider;

use Glue\Application;
use Glue\Provider;
use Glue\ProviderInterface;

class ImagineProvider extends Provider implements ProviderInterface {

    protected $name = 'imagine';

    public function register(Application $app, array $options = array()) {
        $config = $app->getConfig($this->name);
        $options += $config;
        $default_factory = 'Gd';

        if (empty($config['factory'])) {
            $options['factory'] = $default_factory;
        }

        $class = sprintf('\Imagine\%s\Imagine', $options['factory']);

        if (!class_exists($class)) {
            throw new \RuntimeException($class. ' does not exist.');
        }

        return new $class;
    }

}