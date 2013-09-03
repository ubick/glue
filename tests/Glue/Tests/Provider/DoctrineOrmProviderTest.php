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
            'entity.path' => array('Glue\Tests\Fixtures\Entity')
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
        $repo = $em->getRepository('Glue\Tests\Fixtures\Entity\Car');

        $car = new Car();
        
        $car->setYear(2013);
//
//        
//        $em->persist($car);
////        $em->flush();
//        
//        $result = $repo->findOneById(1);
//        
//        echo "<pre>";
//        var_dump($result->getYear());
//        echo "</pre>";
//        die();
        

//        $this->assertTrue(array_key_exists('memory', $params));
//        $this->assertEquals(123, $dbal->fetchColumn("SELECT 123"));
    }

}