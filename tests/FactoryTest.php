<?php

namespace Aguimaras\Tests;

use Aguimaraes\Adapter\APCu;
use Aguimaraes\Adapter\Dummy;
use Aguimaraes\CircuitBreaker\Factory;
use Aguimaraes\CircuitBreakerInterface;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
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
}
