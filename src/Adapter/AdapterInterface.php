<?php declare(strict_types = 1);

namespace Aguimaraes\Adapter;

interface AdapterInterface
{
    /**
     * @param string $service
     *
     * @return int
     */
    public function getErrorCount(string $service = 'default'): int;

    /**
     * @param string $service
     * @param int $value
     */
    public function incrementErrorCount(string $service = 'default', int $value = 1): void;

    /**
     * @param string $service
     *
     * @return void
     */
    public function decrementErrorCount(string $service = 'default'): void;

    /**
     * @param string $service
     * @param int $ttl
     *
     * @return void
     */
    public function breakCircuit(string $service = 'default', int $ttl = 10): void;

    /**
     * @param string $service
     * @return bool
     */
    public function isCircuitBroke(string $service = 'default'): bool;

    /**
     * @param string $service
     *
     * @return int
     */
    public function updateLastCheck(string $service = 'default'): int;
}
