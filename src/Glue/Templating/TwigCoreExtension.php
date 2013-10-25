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

    public function asset(\Twig_Environment $twig, $uri, $cdn = '')
    {
        $base_url = $this->app->getConfig('asset.dir');
        $cdn_domains = $this->app->getConfig('cdn.domains');
        $base_cdn = '';
        
        if (!empty($cdn) && !empty($cdn_domains)) {
            foreach ($cdn_domains as $tag => $domain) {
                if ($cdn == $tag) {
                    $base_cdn = $domain;
                    break;
                }
            }
        }

        if (empty($base_url)) {
            $base_url = $this->app->getRequest()->getBasePath();
        }        
        
        if (!empty($base_cdn)) {
            $base_url = 'http://' . $base_cdn;
        }

        return $base_url . '/' . ltrim($uri, '/');
    }

    public function getName()
    {
        return 'glue';
    }

}