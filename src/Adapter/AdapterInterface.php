<?php declare(strict_types=1);

namespace Aguimaraes\Adapter;

interface AdapterInterface
{
    /**
     * @param string $service
     * @param int $value
     */
    public function setErrorCount(string $service, int $value);

    /**
     * @param string $service
     *
     * @return int
     */
    public function getErrorCount(string $service): int;

    /**
     * @param string $service
     *
     * @return int
     */
    public function getLastCheck(string $service): int;

    /**
     * @param string $service
     *
     * @return int
     */
    public function updateLastCheck(string $service): int;
}
