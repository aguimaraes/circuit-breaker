<?php declare(strict_types = 1);

namespace Aguimaraes\Adapter;

use Exception;

class APCu implements AdapterInterface
{
    private $prefix = 'circuit_breaker';

    /**
     * Increment the service control counter
     *
     * @param string $service
     * @param int $value
     */
    public function incrementControl(string $service = 'default', int $value = 1): void
    {
        apcu_inc(
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
        apcu_dec(
            sprintf('%s.%s.control', $this->prefix, $service),
            $value
        );
    }

    /**
     * Returns the service control counter
     *
     * @param string $service
     * @return int
     */
    public function getControl(string $service): int
    {
        return (int)apcu_fetch(
            sprintf('%s.%s.control', $this->prefix, $service)
        );
    }

    /**
     * Mark the service as broken
     *
     * @param string $service
     */
    public function circuitBreak(string $service = 'default', int $ttl): void
    {
        apcu_store(
            sprintf('%s.%s.control', $this->prefix, $service),
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
        apcu_exists(
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
        return (int)apcu_fetch(
            sprintf('%s.%s.break', $this->prefix, $service)
        );
    }

    /**
     * Returns how many time rest to expire the break
     *
     * @param string $service
     * @return int
     * @throws Exception
     */
    public function getBreakTTL(string $service = 'default'): int
    {
        throw new \Exception('Not Supported');
    }
}
