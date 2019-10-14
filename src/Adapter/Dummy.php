<?php declare(strict_types=1);

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
    public function getErrorCount(string $service): int
    {
        return $this->errorCount[$service] ?? 0;
    }

    /**
     * @inheritdoc
     */
    public function setErrorCount(string $service, int $value)
    {
        $this->errorCount[$service] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getLastCheck(string $service):int
    {
        return $this->lastCheck[$service] ?? 0;
    }

    /**
     * @inheritdoc
     */
    public function updateLastCheck(string $service): int
    {
        return $this->lastCheck[$service] = time();
    }
}
