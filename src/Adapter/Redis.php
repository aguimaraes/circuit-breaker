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
    public function getErrorCount(string $service = 'default'): int
    {
        return (int)$this->redis->get(
            sprintf('%s.%s.control', $this->prefix, $service)
        );
    }

    /**
     * @inheritdoc
     */
    public function incrementErrorCount(string $service = 'default', int $value = 1): void
    {
        $this->redis->incrBy(
            sprintf('%s.%s.threshold', $this->prefix, $service),
            $value
        );
        $this->$this->updateLastCheck($service);
    }

    /**
     * @inheritdoc
     */
    public function decrementErrorCount(string $service = 'default', int $value = 1): void
    {
        $this->redis->decrBy(
            sprintf('%s.%s.threshold', $this->prefix, $service),
            $value
        );
        $this->$this->updateLastCheck($service);
    }

    /**
     * @inheritdoc
     */
    public function breakCircuit(string $service = 'default', int $ttl = 10): void
    {
        $this->redis->set(
            sprintf('%s.%s.break', $this->prefix, $service),
            time()
        );
        $this->redis->expire(
            sprintf('%s.%s.break', $this->prefix, $service),
            $ttl
        );
    }

    /**
     * @inheritdoc
     */
    public function isCircuitBroke(string $service = 'default'): bool
    {
        return (bool) $this->redis->exists(
            sprintf('%s.%s.break', $this->prefix, $service)
        );
    }

    /**
     * @param string $service
     *
     * @return int
     */
    public function updateLastCheck(string $service = 'default'): int
    {
        $this->redis->set(
            sprintf('%s.%s.last_check', $this->prefix, $service),
            time()
        );
    }
}
