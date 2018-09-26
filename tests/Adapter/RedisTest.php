<?php

namespace Aguimaraes\Tests;

use Aguimaraes\Adapter\Redis;
use PHPUnit\Framework\TestCase;

class RedisTest extends TestCase
{
    public function testErrorCountHandling()
    {
        $v = 40;
        $redis = new Redis($this->mockErrorCountClient($v), 'test-prefix');
        $redis->setErrorCount('test-service', $v);

        $this->assertEquals($v, $redis->getErrorCount('test-service'));
    }

    private function mockErrorCountClient(int $v)
    {
        $stub = $this->createMock(\Predis\ClientInterface::class);
        $stub->expects($this->exactly(3))
            ->method('__call')
            ->withConsecutive(
                ['set', ['test-prefix.test-service.error_count', $v]],
                ['exists', ['test-prefix.test-service.error_count']],
                ['get', ['test-prefix.test-service.error_count']]
            )->will($this->onConsecutiveCalls(null, true, $v));

        return $stub;
    }
}
