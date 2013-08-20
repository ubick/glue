<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue;

use Glue\Application;

interface ProviderInterface {
    public function register(Application $app, array $options = array());
    public function getName();
}