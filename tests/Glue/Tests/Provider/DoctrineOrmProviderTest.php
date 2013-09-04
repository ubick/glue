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
use Doctrine\ORM\Tools\SchemaTool;

class DoctrineOrmProviderTest extends \PHPUnit_Framework_TestCase
{

    private $app;
    private $em;

    protected function setUp()
    {
        $this->app = new Application();

        $options = array(
            'driver' => 'pdo_sqlite',
            'memory' => true
        );

        $this->app->register(new DoctrineOrmProvider(), $options);
        $this->em = $this->app->getProvider('doctrine.orm');

        $schemaTool = new SchemaTool($this->em);
        $classes = array(
            $this->em->getClassMetadata('Glue\Tests\Fixtures\Entity\Car'),
        );

        $schemaTool->createSchema($classes);
    }

    protected function tearDown()
    {
        $this->app = null;
        $this->em = null;
    }

    public function testRegister()
    {
        $this->assertInstanceof('Doctrine\Orm\EntityManager', $this->em);
    }

    public function testEntity()
    {
        $car = new Car(10, 'BMW');
        $this->em->persist($car);
        $this->em->flush();

        $entity = $this->em->getRepository('Glue\Tests\Fixtures\Entity\Car')->findOneById(10);

        $this->assertEquals('BMW', $entity->getModel());
    }

}