<?php declare(strict_types=1);

namespace Aguimaraes\Tests\Adapter;

use Aguimaraes\Adapter\Redis;
use PHPUnit\Framework\TestCase;
use Predis\ClientInterface;

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

    private function mockErrorCountClient(int $v): ClientInterface
    {
        $stub = $this->createMock(ClientInterface::class);
        $stub->expects($this->exactly(3))
            ->method('__call')
            ->withConsecutive(
                ['set', ['test-prefix.test-service.error_count', $v]],
                ['exists', ['test-prefix.test-service.error_count']],
                ['get', ['test-prefix.test-service.error_count']]
            )->will($this->onConsecutiveCalls(null, true, $v));

        return $stub;
    }

    private function mockLastCheckClient(): ClientInterface
    {
        $lastUpdated = null;

        $stub = $this->createMock(ClientInterface::class);
        $stub->expects($this->exactly(3))
            ->method('__call')
            ->withConsecutive(
                ['set', $this->callback(static function ($args) use (&$lastUpdated) {
                    if ($args[0] === 'test-prefix.another-test-service.last_check' && $args[1] > 0) {
                        $lastUpdated = $args[1];
                        return true;
                    }
                    return false;
                })],
                ['exists', ['test-prefix.another-test-service.last_check']],
                ['get', ['test-prefix.another-test-service.last_check']]
            )
            ->will(
                $this->onConsecutiveCalls(
                    null,
                    true,
                    $this->returnCallback(static function () use (&$lastUpdated) {
                        return $lastUpdated;
                    })
                )
            );

        return $stub;
    }
}
