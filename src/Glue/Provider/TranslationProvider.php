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
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\XliffFileLoader;

class TranslationProvider extends Provider implements ProviderInterface
{

    protected $name = 'translator';

    public function register(Application $app)
    {
        $translator = new Translator('en', new MessageSelector());
        $translator->setFallbackLocale('en');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addLoader('xliff', new XliffFileLoader());

        return $translator;
    }

}