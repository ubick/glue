<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue\Tests\Provider;

use Glue\Application;
use Glue\Provider\DoctrineDBALProvider;

class DoctrineDBALProviderTest extends \PHPUnit_Framework_TestCase
{

    protected $app;

    protected function setUp()
    {
        $this->app = new Application();

        $options = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        $this->app->register(new DoctrineDBALProvider(), $options);
    }

    public function testRegister()
    {
        $dbal = $this->app->getProvider('doctrine.dbal');
        $this->assertInstanceof('Doctrine\DBAL\Connection', $dbal);
    }

    public function testSingleConnection()
    {
        $dbal = $this->app->getProvider('doctrine.dbal');
        $params = $dbal->getParams();
 
        $this->assertTrue(array_key_exists('memory', $params));
        $this->assertInstanceof('Doctrine\DBAL\Driver\PDOSqlite\Driver', $dbal->getDriver());
        $this->assertEquals(123, $dbal->fetchColumn("SELECT 123"));
    }

}