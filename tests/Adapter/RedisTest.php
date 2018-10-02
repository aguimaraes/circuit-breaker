<?php

namespace Aguimaraes\Tests\Adapter;

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

    public function testLastCheck()
    {
        $redis = new Redis($this->mockLastCheckClient(), 'test-prefix');
        $time = $redis->updateLastCheck('another-test-service');

        $this->assertEquals($time, $redis->getLastCheck('another-test-service'));
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

    private function mockLastCheckClient()
    {
        $stub = $this->createMock(\Predis\ClientInterface::class);
        $stub->expects($this->exactly(3))
            ->method('__call')
            ->withConsecutive(
                ['set', $this->callback(function($args) {
                    return $args[0] === 'test-prefix.another-test-service.last_check'
                        && $args[1] > 0;
                })],
                ['exists', ['test-prefix.another-test-service.last_check']],
                ['get', ['test-prefix.another-test-service.last_check']]
            )->will($this->onConsecutiveCalls(null, true));

        return $stub;
    }
}
