<?php

namespace Aguimaraes\Adapter;

use PHPUnit\Framework\TestCase;

class APCuTest extends TestCase
{
    public function testErrorCountHandling()
    {
        $apcu = new APCu();
        $apcu->setErrorCount('test-service', 40);
        $this->assertEquals(40, $apcu->getErrorCount('test-service'));
    }

    public function testLastCheck()
    {
        $apcu = new APCu();
        $time = $apcu->updateLastCheck('another-test-service');
        $this->assertEquals($time, $apcu->getLastCheck('another-test-service'));
    }
}
