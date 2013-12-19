<?php

/*
 *
 * (c) Liviu Panainte <liviu.panainte@gmail.com>
 *
 */

namespace Glue\Tests\Provider;

use Glue\Application;
use Glue\Provider\ImagineProvider;

class ImagineProviderTest extends \PHPUnit_Framework_TestCase {

    private $app;

    protected function setUp() {
        $this->app = new Application();
    }

    public function testRegister() {
        $options = array('factory' => 'Gd');
        $this->app->register(new ImagineProvider(), $options);
        $this->assertEquals('Imagine\Gd\Imagine', get_class($this->app->getProvider('imagine')));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testBadFactory() {
        $dir = __DIR__ . '/../Fixtures/config';
        $this->app->loadConfig($dir);
        $this->app->register(new ImagineProvider());
    }

    protected function tearDown() {
        $this->app = null;
    }

}