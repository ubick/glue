<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Application extends HttpKernel\HttpKernel
{

    private $router;
    private $config;
    private $request;
    private $providers;
    protected $dispatcher;
    public $shared;

    public function __construct()
    {
        $this->shared = array();
        $this->providers = array();
        $resolver = new ControllerResolver($this);
        $env = $this->getEnvironment();

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new EventListener\RedirectListener());
        $this->dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));
        $this->dispatcher->addSubscriber(new ExceptionHandler($env == 'dev'));

        parent::__construct($this->dispatcher, $resolver);
    }

    /**
     * Handles the request and delivers the response.
     *
     * @param Request $request Request to process
     */
    public function run(Request $request = null)
    {
        if (null === $request) {
            $request = Request::createFromGlobals();
        }
        $this->request = $request;

        $response = $this->handle($request);
        $response->send();
        $this->terminate($request, $response);
    }

    public function loadRoutes($path, $options = array())
    {
        $filelocator = new FileLocator($path);
        $routeloader = new YamlFileLoader($filelocator);
        
        $router = new Router($routeloader, $path, $options);
        $matcher = $router->getMatcher();
        $routeCollection = $router->getRouteCollection();
        $context = $router->getContext();
        
        $this->dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher));
        $this->shared['url.generator'] = new UrlGenerator($routeCollection, $context);
        $this->router = $router;
        
        return $router->getRouteCollection();
    }

    public function loadConfig($dir)
    {
        $parameters_file = $dir . '/parameters.yml';
        $common_config_file = $dir . '/config.yml';
        $custom_config_file = $dir . '/config_' . $this->getEnvironment() . '.yml';

        $params_config = array();
        $common_config = array();
        $custom_config = array();

        if (file_exists($parameters_file) && Yaml::parse($parameters_file) !== null) {
            $params_config = Yaml::parse($parameters_file);
        }

        if (file_exists($common_config_file) && Yaml::parse($common_config_file) !== null) {
            $common_config = Yaml::parse($common_config_file);
        }

        if (file_exists($custom_config_file) && Yaml::parse($custom_config_file) !== null) {
            $custom_config = Yaml::parse($custom_config_file);
        }

        $this->config = array_replace_recursive($common_config, $custom_config, $params_config);

        return $this->config;
    }

    public function getConfig($key = null)
    {
        if (!$key) {
            return $this->config;
        }

        if (empty($this->config[$key])) {
            return array();
        }

        return $this->config[$key];
    }

    /**
     * Registers a provider.
     *
     * @param ProviderInterface $provider A ProviderInterface instance
     * @param array             $values   An array of values that customizes the provider
     *
     * @return Application
     */
    public function register(ProviderInterface $provider, array $options = array())
    {
        $name = $provider->getName();
        $config = $this->getConfig($name);

        if (empty($config)) {
            $config = $options;
        }

        $options = array_replace($config, $options);

        $this->providers[$name] = $provider->register($this, $options);

        return $this;
    }

    public function getProvider($name)
    {
        if (empty($this->providers[$name])) {
            return null;
        }

        return $this->providers[$name];
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getRoutes()
    {
        return $this->router->getRouteCollection();
    }

    public function getRequestContext()
    {
        return $this->request_context;
    }

    public function getEnvironment(Request $request = null)
    {
        if (null === $request) {
            $request = Request::createFromGlobals();
        }

        $host = $request->server->get('HTTP_HOST');

        if ($host) {
            if (preg_match('/localhost|liv|mic$|(10\.10\.11\.[0-9]{1,3})/i', $host)) {
                return 'dev';
            }
        } else if (preg_match('/dev\.*|liv$|(10\.10\.11\.[0-9]{1,3})/i', php_uname('n'))) {
            return 'dev';
        }

        return 'prod';
    }

    public function setRootDir($dir)
    {
        $this->rootDir = $dir;
    }    
    
    public function getRootDir()
    {
        if (empty($this->rootDir)) {
            $r = new \ReflectionObject($this);
            $this->rootDir = str_replace('\\', '/', dirname($r->getFileName()));
        }

        return $this->rootDir;
    }

}