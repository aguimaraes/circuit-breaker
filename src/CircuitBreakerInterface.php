<?php
namespace Aguimaraes;

use Aguimaraes\Adapter\AdapterInterface;

interface CircuitBreakerInterface
{
    /**
     * @param string $service
     *
     * @return bool
     */
    public function isAvailable($service = 'default');

    /**
     * @param string $service
     */
    public function reportFailure($service = 'default');

    /**
     * @param string $service
     */
    public function reportSuccess($service = 'default');

    /**
     * @param string $service
     * @param int $default
     *
     * @return int
     */
    public function getThreshold($service = 'default', $default = 30);

    /**
     * @param int $value
     * @param string $service
     */
    public function setThreshold($value, $service = 'default');

    /**
     * @param string $service
     * @param int $default
     *
     * @return int
     */
    public function getTimeout($service = 'default', $default = 30);

    /**
     * @param int $value
     * @param string $service
     *
     * @return mixed
     */
    public function setTimeout($value, $service = 'default');

    /**
     * @return AdapterInterface
     */
    public function getAdapter();
}
