<?php declare(strict_types=1);

namespace Aguimaraes;

use Aguimaraes\Adapter\AdapterInterface;

class CircuitBreaker implements CircuitBreakerInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var int
     */
    private $defaultThreshold;

    /**
     * @var array
     */
    private $threshold = [];

    /**
     * @var int
     */
    private $defaultTimeout;

    /**
     * @var array
     */
    private $timeout = [];

    /**
     * @param AdapterInterface $adapter
     * @param int $defaultThreshold The threshold to use when a service has no specified one
     * @param int $defaultTimeout The timeout to use when a service has no specified one
     */
    public function __construct(AdapterInterface $adapter, $defaultThreshold = 30, $defaultTimeout = 30)
    {
        $this->adapter = $adapter;
        $this->defaultThreshold = $defaultThreshold;
        $this->defaultTimeout = $defaultTimeout;
    }

    /**
     * @inheritdoc
     */
    public function isAvailable(string $service): bool
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
    public function reportFailure(string $service)
    {
        $this->adapter->setErrorCount($service, $this->adapter->getErrorCount($service) + 1);
        $this->adapter->updateLastCheck($service);
    }

    /**
     * @inheritdoc
     */
    public function reportSuccess(string $service)
    {
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
    public function getThreshold(string $service): int
    {
        return $this->threshold[$service] ?? $this->defaultThreshold;
    }

    /**
     * @inheritdoc
     */
    public function setThreshold(string $service, int $value)
    {
        $this->threshold[$service] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getTimeout(string $service): int
    {
        return $this->timeout[$service] ?? $this->defaultTimeout;
    }

    /**
     * @inheritdoc
     */
    public function setTimeout(string $service, int $value)
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
