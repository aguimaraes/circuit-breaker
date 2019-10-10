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
     * @var array
     */
    private $threshold = [];

    /**
     * @var array
     */
    private $timeout = [];

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritdoc
     */
    public function isAvailable(string $service = 'default'): bool
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
    public function reportFailure(string $service = 'default')
    {
        $this->adapter->setErrorCount($service, $this->adapter->getErrorCount($service) + 1);
        $this->adapter->updateLastCheck($service);
    }

    /**
     * @inheritdoc
     */
    public function reportSuccess(string $service = 'default')
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
    public function getThreshold(string $service = 'default', int $default = 30): int
    {
        return $this->threshold[$service] ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function setThreshold(int $value, string $service = 'default')
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
