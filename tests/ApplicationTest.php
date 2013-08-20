<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace tests;

use Glue\Application;
use Symfony\Component\HttpFoundation\Request;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    protected $app;

    protected function setUp()
    {
        $this->app = new Application();
    }

    public function testLoadRoutes()
    {
        $path = __DIR__ . '/Fixtures/config/routing.yml';

        $this->assertSame($this->app->loadRoutes($path), $this->app->getRoutes());
    }

    public function testLoadConfig()
    {
        $dir = __DIR__ . '/Fixtures/config';
        $expected = array('data' => 'content');

        $this->assertSame($this->app->loadConfig($dir), $expected);
        $this->assertSame($this->app->loadConfig($dir), $this->app->getConfig());
    }

    public function testRegister()
    {
        $app = new Application();
        $provider = $this->getMock('Glue\ProviderInterface');

        $this->assertSame($app, $app->register($provider));
    }

    public function testGetEnvironment()
    {
        $app = new Application();
        $request = Request::create('/');

        $request->server->set('HTTP_HOST', 'google.com');
        $this->assertEquals($app->getEnvironment($request), 'prod');

        $request->server->set('HTTP_HOST', 'staging.google.com');
        $this->assertEquals($app->getEnvironment($request), 'prod');

        $request->server->set('HTTP_HOST', 'some-new-project.liv');
        $this->assertEquals($app->getEnvironment($request), 'dev');

        $request->server->set('HTTP_HOST', 'some-new-project.mic');
        $this->assertEquals($app->getEnvironment($request), 'dev');

        $request->server->set('HTTP_HOST', '10.10.11.195');
        $this->assertEquals($app->getEnvironment($request), 'dev');

        $request->server->set('HTTP_HOST', '10.10.11.199');
        $this->assertEquals($app->getEnvironment($request), 'dev');
    }

}