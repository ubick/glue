<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace tests;

use Glue\Application;
use Symfony\Component\HttpFoundation\Request;

class ApplicationText extends \PHPUnit_Framework_TestCase
{

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