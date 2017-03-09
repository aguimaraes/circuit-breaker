<?php declare(strict_types = 1);

namespace Aguimaraes\Adapter;

class Dummy implements AdapterInterface
{
    /**
     * @var array
     */
    private $errorCount = [];

    /**
     * @var array
     */
    private $lastCheck = [];

    /**
     * @inheritdoc
     */
    public function setErrorCount(string $service = 'default', int $value = 0): void
    {
        $this->errorCount[$service] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getErrorCount(string $service = 'default'): int
    {
        return $this->errorCount[$service] ?? 0;
    }

    /**
     * @inheritdoc
     */
    public function incrErrorCount(string $service = 'default', int $value = 1): void
    {
        return $this->errorCount[$service] ?? 0;
    }

    /**
     * @inheritdoc
     */
    public function decrErrorCount(string $service = 'default', int $value = 1): void
    {
        return $this->errorCount[$service] ?? 0;
    }

    /**
     * @inheritdoc
     */
    public function getLastCheck(string $service = 'default'): int
    {
        return $this->lastCheck[$service] ?? 0;
    }

    /**
     * @inheritdoc
     */
    public function updateLastCheck(string $service = 'default', int $timeout): void
    {
        $this->lastCheck[$service] = time();
    }
}
