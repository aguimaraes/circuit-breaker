<?php declare(strict_types = 1);

namespace Aguimaraes;

use Aguimaraes\Adapter\AdapterInterface;

class CircuitBreaker implements CircuitBreakerInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var array
     */
    private $threshold = [];

    /**
     * @var array
     */
    private $timeout = [];

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritdoc
     */
    public function isAvailable(string $service = 'default'): bool
    {
        if ($this->isServiceBroken($service)) {
            return false;
        }

        $counter = $this->adapter->getControl($service);
        if ($counter > $this->getThreshold($service)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $service
     * @return bool
     */
    private function isServiceBroken(string $service): bool
    {
        $isBroken = $this->adapter->isBroken($service);

        if ($isBroken === false) {
            return false;
        }

        $toleranceTime = $this->adapter->getBrokenTime($service) + $this->getTimeout($service);
        if ($toleranceTime >= time()) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function reportFailure(string $service = 'default'): void
    {
        $this->adapter->incrementControl($service);
    }

    /**
     * @inheritdoc
     */
    public function reportSuccess(string $service = 'default'): void
    {
        $control = $this->adapter->getControl($service);

        if ($control > $this->getThreshold($service)) {
            $value = $control - ($this->getThreshold($service) - 1);

            $this->adapter->decrementControl($service, $value);
            return;
        }

        $this->adapter->decrementControl($service);
    }

    /**
     * @inheritdoc
     */
    public function getThreshold(string $service = 'default', int $default = 30): int
    {
        return $this->threshold[$service] ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function setThreshold(int $value, string $service = 'default'): void
    {
        $this->threshold[$service] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getTimeout(string $service = 'default', int $default = 30): int
    {
        return $this->timeout[$service] ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function setTimeout(int $value, string $service = 'default')
    {
        $this->timeout[$service] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }
}
