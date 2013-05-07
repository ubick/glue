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
use Symfony\Component\HttpFoundation\Session\Session;

class SessionProvider extends Provider implements ProviderInterface
{

    protected $name = 'session';

    public function register(Application $app)
    {
        $session = new Session();

        return $session;
    }

}