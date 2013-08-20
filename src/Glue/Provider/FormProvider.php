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
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;

class FormProvider extends Provider implements ProviderInterface
{

    protected $name = 'form';

    public function register(Application $app, array $options = array())
    {
        $secret = $app->getConfig('form.secret');
        $app->shared['csrf_provider'] = new DefaultCsrfProvider($secret);
        $extensions = array(
            new CsrfExtension($app->shared['csrf_provider']),
            new HttpFoundationExtension(),
        );

        $factory = Forms::createFormFactoryBuilder()
            ->addExtensions($extensions)
            ->addTypeExtensions(array())
            ->addTypeGuessers(array())
            ->getFormFactory();

        return $factory;
    }

}