<?php declare(strict_types=1);

namespace Aguimaraes;

interface CircuitBreakerInterface
{
    /**
     * @param string $service
     *
     * @return bool
     */
    public function isAvailable(string $service): bool;

    /**
     * @param string $service
     */
    public function reportFailure(string $service);

    /**
     * @param string $service
     */
    public function reportSuccess(string $service);
}
