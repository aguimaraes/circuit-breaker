<?php declare(strict_types = 1);

namespace Aguimaraes;

interface CircuitBreakerInterface
{
    /**
     * @param string $service
     *
     * @return bool
     */
    public function isAvailable(string $service = 'default'): bool;

    /**
     * @param string $service
     */
    public function reportFailure(string $service = 'default'): void;

    /**
     * @param string $service
     */
    public function reportSuccess(string $service = 'default'): void;

    /**
     * @param string $service
     * @param int $default
     *
     * @return int
     */
    public function getThreshold(string $service = 'default', int $default = 30): int;

    /**
     * @param int $value
     * @param string $service
     */
    public function setThreshold(int $value, string $service = 'default'): void;

    /**
     * @param string $service
     * @param int $default
     *
     * @return int
     */
    public function getTimeout(string $service = 'default', int $default = 30): int;

    /**
     * @param int $value
     * @param string $service
     *
     * @return mixed
     */
    public function setTimeout(int $value, string $service = 'default');
}
