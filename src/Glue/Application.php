<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Application extends HttpKernel\HttpKernel
{

    private $routes;
    private $config;
    private $request;
    private $request_context;
    private $providers;
    protected $dispatcher;
    public $shared;

    public function __construct()
    {
        $this->shared = array();
        $this->providers = array();
        $this->routes = new RouteCollection();
        $this->request_context = new Routing\RequestContext();
        $matcher = new Routing\Matcher\UrlMatcher($this->routes, $this->request_context);
        $resolver = new ControllerResolver($this);
        $env = $this->getEnvironment();

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new EventListener\RedirectListener());
        $this->dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher));
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

    public function loadRoutes($path)
    {
        $filelocator = new FileLocator($path);
        $routeloader = new YamlFileLoader($filelocator);
        $this->routes->addCollection($routeloader->load($path));
        
        $app->shared['url.generator'] = new UrlGenerator($this->routes, $app->request_context());         
    }

    public function loadConfig($dir)
    {
        $parameters_file = $dir . '/parameters.yml';

        if (!file_exists($parameters_file)) {
            die('Please specify a parameters.yml config file.');
        }

        $params_config = Yaml::parse($parameters_file) ? : array();
        $common_config = Yaml::parse($dir . '/config.yml');
        $custom_config = Yaml::parse($dir . '/config_' . $this->getEnvironment() . '.yml') ? : array();

        $this->config = array_merge_recursive($common_config, $custom_config, $params_config);
    }

    public function getConfig($key = null)
    {
        if (!$key) {
            return $this->config;
        }

        if (empty($this->config[$key])) {
            throw new \Exception('Non-existant "' . $key . '" config.');
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
    public function register(ProviderInterface $provider, array $values = array())
    {
        $this->providers[$provider->getName()] = $provider->register($this);

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
        return $this->routes;
    }

    public function getRequestContext()
    {
        return $this->request_context;
    }

    public function getEnvironment()
    {
        $request = Request::createFromGlobals();
        $host = $request->server->get('HTTP_HOST');

        if (preg_match('/localhost|liv|mic$|(10\.10\.11\.195)|(10\.10\.11\.199)/i', $host)) {
            return 'dev';
        }

        return 'prod';
    }

}