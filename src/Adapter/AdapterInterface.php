<?php declare(strict_types = 1);

namespace Aguimaraes\Adapter;

interface AdapterInterface
{
    /**
     * @param string $service
     * @param int $value
     */
    public function setErrorCount(string $service = 'default', int $value = 0): void;

    /**
     * @param string $service
     *
     * @return int
     */
    public function getErrorCount(string $service = 'default'): int;

    /**
     * @param string $service
     *
     * @return int
     */
    public function getLastCheck(string $service = 'default'): int;

    /**
     * @param string $service
     *
     * @return int
     */
    public function updateLastCheck(string $service = 'default'): int;
}
