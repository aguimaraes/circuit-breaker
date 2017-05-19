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

    /**
     * @var Stats
     */
    private $stats;

    /**
     * @param AdapterInterface $adapter
     * @param Stats|null $stats
     */
    public function __construct(AdapterInterface $adapter, Stats $stats = null)
    {
        $this->adapter = $adapter;
        $this->stats = empty($stats) ? new Stats() : $stats;
    }

    /**
     * @inheritdoc
     */
    public function isAvailable($service = 'default')
    {
        $errorCount = $this->adapter->getErrorCount($service);

        if ($errorCount <= $this->getThreshold($service)) {
            $this->stats->available($service);

            return true;
        }

        $lastCheck = $this->adapter->getLastCheck($service);

        if ($lastCheck + $this->getTimeout($service) >= time()) {
            $this->stats->notAvailable($service);

            return false;
        }

        $this->adapter->updateLastCheck($service);

        $this->stats->available($service);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function reportFailure($service = 'default')
    {
        $this->stats->failure($service);
        $this->adapter->setErrorCount($service, $this->adapter->getErrorCount($service) + 1);
        $this->adapter->updateLastCheck($service);
    }

    /**
     * @inheritdoc
     */
    public function reportSuccess($service = 'default')
    {
        $this->stats->success($service);
        $errorCount = $this->getAdapter()->getErrorCount($service);
        $threshold = $this->getThreshold($service);

        if ($errorCount === 0) {
            return;
        }

        if ($errorCount > $threshold) {
            $this->adapter->setErrorCount(
                $service,
                $threshold - 1
            );

            return;
        }

        $this->adapter->setErrorCount(
            $service,
            $errorCount - 1
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

    /**
     * @return Stats
     */
    public function getStats()
    {
        return $this->stats;
    }
}
