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

        $this->shared['url.generator'] = new UrlGenerator($this->routes, $this->request_context);

        return $this->routes;
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
    public function register(ProviderInterface $provider)
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

    public function getEnvironment(Request $request = null)
    {
        if (null === $request) {
            $request = Request::createFromGlobals();
        }

        $host = $request->server->get('HTTP_HOST');

        if ($host) {
            if (preg_match('/localhost|liv|mic$|(10\.10\.11\.195)|(10\.10\.11\.199)/i', $host)) {
                return 'dev';
            }
        } else if (preg_match('/dev\.*|liv$|(10\.10\.11\.195)/i', php_uname('n'))) {
            return 'dev';
        }

        return 'prod';
    }

}