<?php declare(strict_types=1);

namespace Aguimaras\Tests\Factory;

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
        $this->assertEquals(8, $cb->getThreshold('default'));
        $this->assertEquals(8, $cb->getThreshold('no config'));
        $this->assertEquals(123, $cb->getTimeout('default'));
        $this->assertEquals(123, $cb->getTimeout('no config'));
    }

    public function testInstanceCreationWithoutArguments()
    {
        $factory = new Factory();
        $cb = $factory->createCircuitBreaker();

        $this->assertInstanceOf(CircuitBreakerInterface::class, $cb);
        $this->assertInstanceOf(Dummy::class, $cb->getAdapter());
        $this->assertEquals(10, $cb->getThreshold(__METHOD__));
        $this->assertEquals(120, $cb->getTimeout(__METHOD__));
    }
}
