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
        if ($this->adapter->getErrorCount($service) > $this->getThreshold($service)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function reportFailure(string $service = 'default'): void
    {
        $this->adapter->incrErrorCount($service, 1);
        $this->adapter->updateLastCheck($service);
    }

    /**
     * @inheritdoc
     */
    public function reportSuccess(string $service = 'default'): void
    {
        if ($this->adapter->getErrorCount($service) > $this->getThreshold($service)) {
            $this->adapter->setErrorCount(
                $service,
                $this->getThreshold($service) - 1
            );
            return;
        }

        if ($error_count > 0) {
            $this->adapter->decrErrorCount($service, 1);
        }
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
}
