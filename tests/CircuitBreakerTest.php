<?php declare(strict_types=1);

namespace Aguimaraes\Tests;

use Aguimaraes\Adapter\Dummy;
use Aguimaraes\CircuitBreaker;
use PHPUnit\Framework\TestCase;

class CircuitBreakerTest extends TestCase
{
    public function testClosedCircuit()
    {
        $cb = new CircuitBreaker(new Dummy());

        $cb->setThreshold('default', 1);

        $this->assertEquals(true, $cb->isAvailable('default'));

        $cb->reportFailure('default');

        $this->assertTrue($cb->isAvailable('default'));
    }

    public function testOpenCircuit()
    {
        $cb = new CircuitBreaker(new Dummy());

        $cb->setThreshold('default', 0);

        $cb->reportFailure('default');

        $this->assertFalse($cb->isAvailable('default'));
        $this->assertTrue($cb->isAvailable('unknown'));
    }

    public function testHalfOpenCircuit()
    {
        $dummy = new Dummy();

        $cb = new CircuitBreaker($dummy);

        $cb->setThreshold('default', 1);

        $cb->reportFailure('default');

        $this->assertTrue($cb->isAvailable('default'));

        $cb->reportFailure('default');

        $this->assertFalse($cb->isAvailable('default'));

        $cb->setTimeout('default', -30);

        $this->assertTrue($cb->isAvailable('default'));

        $cb->reportFailure('default');

        $cb->setTimeout('default', 30);

        $this->assertFalse($cb->isAvailable('default'));
    }

    public function testReportSuccessGoingNegative()
    {
        $dummy = new Dummy();

        $cb = new CircuitBreaker($dummy);

        $cb->setThreshold('default', 1);
        $cb->setTimeout('default', 1);

        $cb->reportSuccess('default');
        $cb->reportSuccess('default');

        $this->assertEquals(0, $cb->getAdapter()->getErrorCount('default'));
    }

    public function testReportSuccessWhenAboveThreshold()
    {
        $dummy = new Dummy();

        $cb = new CircuitBreaker($dummy);

        $cb->setThreshold('default', 1);
        $cb->setTimeout('default', 30);

        $cb->reportFailure('default');
        $cb->reportFailure('default');
        $cb->reportFailure('default');

        $cb->reportSuccess('default');

        $this->assertEquals(0, $cb->getAdapter()->getErrorCount('default'));
    }

    public function testReportSuccessWhenBelowThreshold()
    {
        $dummy = new Dummy();

        $cb = new CircuitBreaker($dummy);

        $cb->setThreshold('default', 4);
        $cb->setTimeout('default', 30);

        $cb->reportFailure('default');
        $cb->reportFailure('default');
        $cb->reportFailure('default');

        $cb->reportSuccess('default');

        $this->assertEquals(2, $cb->getAdapter()->getErrorCount('default'));
    }
}
