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
     * Increment the service control counter
     *
     * @param string $service
     * @param int $value
     */
    public function incrementControl(string $service = 'default', int $value = 1): void
    {
        $this->redis->incrby(
            sprintf('%s.%s.control', $this->prefix, $service),
            $value
        );
    }

    /**
     * Decrement the service control counter
     *
     * @param string $service
     * @param int $value
     */
    public function decrementControl(string $service = 'default', int $value = 1): void
    {
        $this->redis->decrby(
            sprintf('%s.%s.control', $this->prefix, $service),
            $value
        );
    }

    /**
     * @inheritdoc
     */
    public function getControl(string $service): int
    {
        return (int) $this->redis->get(
            sprintf('%s.%s.control', $this->prefix, $service)
        );
    }

    /**
     * Mark the service as broken
     *
     * @param string $service
     * @param int $ttl
     */
    public function circuitBreak(string $service = 'default', int $ttl): void
    {
        $this->redis->set(
            sprintf('%s.%s.break', $this->prefix, $service),
            time(),
            $ttl
        );
    }

    /**
     * Check if service is broken
     *
     * @param string $service
     * @return bool
     */
    public function isBroken(string $service = 'default'): bool
    {
        return (bool) $this->redis->exists(
            sprintf('%s.%s.break', $this->prefix, $service)
        );
    }

    /**
     * Return the time when break is started
     *
     * @param string $service
     * @return int
     */
    public function getBrokenTime(string $service = 'default'): int
    {
        return (int) $this->redis->get(
            sprintf('%s.%s.break', $this->prefix, $service)
        );
    }

    /**
     * Returns how many time rest to expire the break
     *
     * @param string $service
     * @return int
     */
    public function getBreakTTL(string $service = 'default'): int
    {
        return $this->redis->ttl(
            sprintf('%s.%s.break', $this->prefix, $service)
        );
    }
}
