<?php declare(strict_types = 1);

namespace Aguimaraes\Adapter;

interface AdapterInterface
{
    /**
     * @param string $service
     * @param int $value
     */
    public function setErrorCount($service = 'default', $value = 0);

    /**
     * @param string $service
     *
     * @return int
     */
    public function getErrorCount($service = 'default');

    /**
     * @param string $service
     *
     * @return int
     */
    public function getLastCheck($service = 'default');

    /**
     * @param string $service
     *
     * @return int
     */
    public function updateLastCheck($service = 'default');
}
