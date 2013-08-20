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

class SwiftMailerProvider extends Provider implements ProviderInterface
{

    protected $name = 'mailer';

    public function register(Application $app, array $options = array())
    {
        $transport = \Swift_SmtpTransport::newInstance();
        $mailer = \Swift_Mailer::newInstance($transport);

        return $mailer;
    }

}