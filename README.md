Glue, a lightweight PHP 5.3 framework based on Symfony2 components
=============================

Glue is an easy to use PHP framework heavily inspired by [Silex][1] and [Symfony2][2].
Its extremly lightweight nature allows for Rapid Application Development using 
industry standard components and best practices.


```php
<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Glue\Application();

// specify a config directory
$app->loadConfig(__DIR__ . '/app/config');

// specify a routing Yaml file
$app->loadRoutes(__DIR__ . '/app/config/routing.yml');

// load the twig templating engine
$app->register(new Glue\Provider\TwigProvider());

$app->run();
```

## Installation

The recommended way to install Glue is [through
composer](http://getcomposer.org). Just create a `composer.json` file and
run the `php composer.phar install` command to install it:

    {
        "require": {
            "ubick/glue": "dev-master"
        }
    }

## License

Glue is licensed under the MIT license.

[1]: https://github.com/fabpot/Silex
[2]: https://github.com/symfony/symfony
