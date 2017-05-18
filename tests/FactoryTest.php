<?php
namespace Aguimaras\Tests;

use Aguimaraes\Adapter\APCu;
use Aguimaraes\Adapter\Dummy;
use Aguimaraes\CircuitBreaker\Factory;
use Aguimaraes\CircuitBreakerInterface;
use League\StatsD\Client;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceCreationWithArguments()
    {
        $factory = new Factory(new APCu(), 8, 123);
        $cb = $factory->createCircuitBreaker();

        $this->assertInstanceOf(CircuitBreakerInterface::class, $cb);
        $this->assertInstanceOf(APCu::class, $cb->getAdapter());
        $this->assertEquals(8, $cb->getThreshold());
        $this->assertEquals(123, $cb->getTimeout());
    }

    public function testInstanceCreationWithoutArguments()
    {
        $factory = new Factory();
        $cb = $factory->createCircuitBreaker();

        $this->assertInstanceOf(CircuitBreakerInterface::class, $cb);
        $this->assertInstanceOf(Dummy::class, $cb->getAdapter());
        $this->assertEquals(10, $cb->getThreshold());
        $this->assertEquals(120, $cb->getTimeout());
    }

    public function testInstanceCreationWithStats()
    {
        $factory = new Factory();

        $factory->addStats(new Client());

        $cb = $factory->createCircuitBreaker();

        $this->assertInstanceOf(Client::class, $cb->getStats()->getClient());
    }
}
