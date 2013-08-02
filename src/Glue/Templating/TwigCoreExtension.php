<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue\Templating;

use Glue\Application;

class TwigCoreExtension extends \Twig_Extension
{

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getFunctions()
    {
        return array(
            'asset' => new \Twig_Function_Method($this, 'asset', array('needs_environment' => true)),
        );
    }

    public function asset(\Twig_Environment $twig, $uri)
    {
        $asset_dir = $this->app->getConfig('asset.dir');

        if (empty($asset_dir)) {
            $asset_dir = $this->app->getRequest()->getBasePath();
        }

        return $asset_dir . '/' . ltrim($uri, '/');
    }

    public function getName()
    {
        return 'glue';
    }

}