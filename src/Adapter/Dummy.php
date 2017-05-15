<?php declare(strict_types = 1);

namespace Aguimaraes\Adapter;

class Dummy implements AdapterInterface
{
    /**
     * @var array
     */
    private $control = [];

    /**
     * @var array
     */
    private $breaks = [];

    /**
     * Increment the service control counter
     *
     * @param string $service
     * @param int $value
     */
    public function incrementControl(string $service = 'default', int $value = 1): void
    {
        if ($this->control[$service] === null) {
            $this->control[$service] = 0;
        }

        $this->control[$service] = $this->control[$service] + $value;
    }

    /**
     * Decrement the service control counter
     *
     * @param string $service
     * @param int $value
     */
    public function decrementControl(string $service = 'default', int $value = 1): void
    {
        if ($this->control[$service] === null) {
            $this->control[$service] = 0;
        }

        $this->control[$service] = $this->control[$service] - $value;
    }

    /**
     * Returns the service control counter
     *
     * @param string $service
     * @return int
     */
    public function getControl(string $service): int
    {
        return $this->control[$service];
    }

    /**
     * Mark the service as broken
     *
     * @param string $service
     * @param int $ttl
     */
    public function circuitBreak(string $service = 'default', int $ttl): void
    {
        $this->breaks[$service] = [
            'ttl' => $ttl,
            'time' => time()
        ];
    }

    /**
     * Check if service is broken
     *
     * @param string $service
     * @return bool
     */
    public function isBroken(string $service = 'default'): bool
    {
        $break = $this->breaks[$service];

        return (time() >= ($break['time'] + $break['ttl']));
    }

    /**
     * Return the time when break is started
     *
     * @param string $service
     * @return int
     */
    public function getBrokenTime(string $service = 'default'): int
    {
        return $this->breaks[$service]['time'];
    }

    /**
     * Returns how many time rest to expire the break
     *
     * @param string $service
     * @return int
     */
    public function getBreakTTL(string $service = 'default'): int
    {
        $break = $this->breaks[$service];

        if ($this->isBroken($service)) {
            return 0;
        }

        return ($break['time'] + $break['ttl']) - time();
    }
}
