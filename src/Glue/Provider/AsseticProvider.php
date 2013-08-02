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
use Assetic\Extension\Twig\AsseticExtension;
use Assetic\Factory\AssetFactory;
use Assetic\AssetManager;
use Assetic\FilterManager;

class AsseticProvider extends Provider implements ProviderInterface
{

    protected $name = 'assetic';

    public function register(Application $app)
    {
        $root = $app->getConfig('assetic.root');

        $assetic = new AssetFactory($root);
        $assetic->setAssetManager(new AssetManager());
        $assetic->setFilterManager(new FilterManager());
        
        if ($app->getProvider('twig') !== null) {
            $twig = $app->getProvider('twig');
            $twig->addExtension(new AsseticExtension($factory));
        }

        return $assetic;
    }

}