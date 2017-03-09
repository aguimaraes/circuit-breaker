<?php declare(strict_types = 1);

namespace Aguimaraes\Adapter;

use Predis\ClientInterface;

class Redis implements AdapterInterface
{
    /**
     * @var ClientInterface
     */
    private $redis;

    /**
     * @var string
     */
    private $prefix;

    public function __construct(ClientInterface $redis, $prefix = 'circuit_breaker')
    {
        $this->redis = $redis;
        $this->prefix = $prefix;
    }

    /**
     * @inheritdoc
     */
    public function incrErrorCount(string $service = 'default', int $value = 1): void
    {
        $this->redis->incrBy(
            sprintf('%s.%s.error_count', $this->prefix, $service),
            $value
        );
    }

    /**
     * @inheritdoc
     */
    public function decrErrorCount(string $service = 'default', int $value = 1): void
    {
        $this->redis->decrBy(
            sprintf('%s.%s.error_count', $this->prefix, $service),
            $value
        );
    }

    /**
     * @inheritdoc
     */
    public function setErrorCount(string $service = 'default', int $value = 0): void
    {
        $this->redis->set(
            sprintf('%s.%s.error_count', $this->prefix, $service),
            $value
        );
    }

    /**
     * @inheritdoc
     */
    public function getErrorCount(string $service = 'default'): int
    {
        return $this->getKey(
            sprintf('%s.%s.error_count', $this->prefix, $service)
        );
    }

    /**
     * @inheritdoc
     */
    public function updateLastCheck(string $service = 'default', int $timeout): void
    {
        $this->redis->expire(
            sprintf('%s.%s.error_count', $this->prefix, $service),
            time() + $timeout
        );
    }

    /**
     * @param string $key
     *
     * @return int
     */
    private function getKey(string $key): int
    {
        if (!$this->redis->exists($key)) {
            return 0;
        }

        return (int) $this->redis->get($key);
    }
}
