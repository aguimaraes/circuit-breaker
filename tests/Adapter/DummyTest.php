<?php declare(strict_types = 1);

namespace Aguimaraes\Tests;

use Aguimaraes\Adapter\Dummy;
use PHPUnit\Framework\TestCase;

class DummyTest extends TestCase
{
    public function testErrorCountHandling()
    {
        $dummy = new Dummy();
        $dummy->setErrorCount('test-service', 40);

        $this->assertEquals(40, $dummy->getErrorCount('test-service'));
    }

    public function testLastCheck()
    {
        $dummy = new Dummy();
        $time = $dummy->updateLastCheck('another-test-service');

        $this->assertEquals($time, $dummy->getLastCheck('another-test-service'));
    }
}
