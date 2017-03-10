<?php declare(strict_types = 1);

namespace Aguimaraes\Adapter;

interface AdapterInterface
{
    /**
     * Increment the service control counter
     *
     * @param string $service
     * @param int $value
     */
    public function incrementControl(string $service = 'default', int $value = 1): void;

    /**
     * Decrement the service control counter
     *
     * @param string $service
     * @param int $value
     */
    public function decrementControl(string $service = 'default', int $value = 1): void;

    /**
     * Mark the service as broken
     *
     * @param string $service
     */
    public function circuitBreak(string $service = 'default'): void;

    /**
     * Check if service is broken
     *
     * @param string $service
     * @return bool
     */
    public function isBroken(string $service = 'default'): bool;

    /**
     * Return the time when break is started
     *
     * @param string $service
     * @return int
     */
    public function getBrokenTime(string $service = 'default'): int;

    /**
     * Returns how many time rest to expire the break
     *
     * @param string $service
     * @return int
     */
    public function getBreakTTL(string $service = 'default'): int;
}
