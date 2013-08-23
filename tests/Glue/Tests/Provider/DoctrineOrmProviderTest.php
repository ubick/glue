<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue\Tests\Provider;

use Glue\Application;
use Glue\Provider\DoctrineOrmProvider;
use Glue\Tests\Fixtures\Entity\Car;

class DoctrineOrmProviderTest extends \PHPUnit_Framework_TestCase
{

    protected $app;

    protected function setUp()
    {
        $this->app = new Application();

        $options = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        $this->app->register(new DoctrineOrmProvider(), $options);
    }

    public function testRegister()
    {
        $em = $this->app->getProvider('doctrine.orm');
        $this->assertInstanceof('Doctrine\Orm\EntityManager', $em);
    }

    public function testEntity()
    {
        $em = $this->app->getProvider('doctrine.orm');

        $car = new Car();
        $car->setYear(2013);
//        $em->persist($car);

//        $this->assertTrue(array_key_exists('memory', $params));
//        $this->assertInstanceof('Doctrine\DBAL\Driver\PDOSqlite\Driver', $dbal->getDriver());
//        $this->assertEquals(123, $dbal->fetchColumn("SELECT 123"));
    }

}