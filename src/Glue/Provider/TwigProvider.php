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
use Glue\Templating\TwigCoreExtension;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

class TwigProvider extends Provider implements ProviderInterface
{

    protected $name = 'twig';

    public function register(Application $app, array $options = array())
    {
        $config = $app->getConfig();

        if (!empty($config[$this->name])) {
            $fs_twig_loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../../../../../src/' . $config[$this->name]['path']);
            $loader = new \Twig_Loader_Chain(array($fs_twig_loader));
            $twig = new \Twig_Environment($loader);

            // this extension includes the asset() function
            $twig->addExtension(new TwigCoreExtension($app));
            
            // the routing extension is required to reference routes in twig templates
            // e.g <a href="{{ path('Some_route')}}">click</a>
            $twig->addExtension(new RoutingExtension($app->shared['url.generator']));
            
            if ($app->getProvider('translator') !== null) {
                $twig->addExtension(new TranslationExtension($app->getProvider('translator')));
            }

            if ($app->getProvider('form')) {
                $twig_form_templates = array('form_div_layout.html.twig');
                $twig_form_engine = new TwigRendererEngine($twig_form_templates);
                $twig_form_renderer = new TwigRenderer($twig_form_engine, $app->shared['csrf_provider']);

                $twig->addExtension(new FormExtension($twig_form_renderer));

                // add loader for Symfony built-in form templates
                $reflected = new \ReflectionClass('Symfony\Bridge\Twig\Extension\FormExtension');
                $path = dirname($reflected->getFileName()) . '/../Resources/views/Form';
                $loader->addLoader(new \Twig_Loader_Filesystem($path));
            }

            return $twig;
        }

        return false;
    }

}