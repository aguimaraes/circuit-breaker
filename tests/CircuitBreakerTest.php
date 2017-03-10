<?php declare(strict_types = 1);

namespace Aguimaraes\Tests;

use Aguimaraes\Adapter\Dummy;
use Aguimaraes\CircuitBreaker;
use PHPUnit\Framework\TestCase;

class CircuitBreakerTest extends TestCase
{
    public function testClosedCircuit()
    {
        $cb = new CircuitBreaker(new Dummy());

        $cb->setThreshold(1);

        $this->assertEquals(true, $cb->isAvailable());

        $cb->reportFailure();

        $this->assertTrue($cb->isAvailable());
    }

    public function testOpenCircuit()
    {
        $cb = new CircuitBreaker(new Dummy());

        $cb->setThreshold(0);

        $cb->reportFailure();

        $this->assertFalse($cb->isAvailable());
    }

    public function testHalfOpenCircuit()
    {
        $dummy = new Dummy();

        $cb = new CircuitBreaker($dummy);

        $cb->setThreshold(1);

        $cb->reportFailure();

        $this->assertTrue($cb->isAvailable());

        $cb->reportFailure();

        $this->assertFalse($cb->isAvailable());

        $cb->setTimeout(-30);

        $this->assertTrue($cb->isAvailable());

        $cb->reportFailure();

        $cb->setTimeout(30);

        $this->assertFalse($cb->isAvailable());
    }
}
