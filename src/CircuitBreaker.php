<?php

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
    public function isAvailable($service = 'default')
    {
        $errorCount = $this->adapter->getErrorCount($service);

        if ($errorCount <= $this->getThreshold($service)) {
            return true;
        }

        $lastCheck = $this->adapter->getLastCheck($service);

        if ($lastCheck + $this->getTimeout($service) >= time()) {
            return false;
        }

        $this->adapter->updateLastCheck($service);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function reportFailure($service = 'default')
    {
        $this->adapter->setErrorCount($service, $this->adapter->getErrorCount($service) + 1);
        $this->adapter->updateLastCheck($service);
    }

    /**
     * @inheritdoc
     */
    public function reportSuccess($service = 'default')
    {
        if ($this->adapter->getErrorCount($service) > $this->getThreshold($service)) {
            $this->adapter->setErrorCount(
                $service,
                $this->getThreshold($service) - 1
            );
            return;
        }

        $this->adapter->setErrorCount(
            $service,
            $this->adapter->getErrorCount($service) - 1
        );
    }

    /**
     * @inheritdoc
     */
    public function getThreshold($service = 'default', $default = 30)
    {
        return isset($this->threshold[$service]) ? $this->threshold[$service] : $default;
    }

    /**
     * @inheritdoc
     */
    public function setThreshold($value, $service = 'default')
    {
        $this->threshold[$service] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getTimeout($service = 'default', $default = 30)
    {
        return isset($this->timeout[$service]) ? $this->timeout[$service] : $default;
    }

    /**
     * @inheritdoc
     */
    public function setTimeout($value, $service = 'default')
    {
        $this->timeout[$service] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
