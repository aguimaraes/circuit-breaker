<?php declare(strict_types = 1);

namespace Aguimaraes\Adapter;

class APCu implements AdapterInterface
{
    private $prefix = 'circuit_breaker';

    /**
     * @inheritdoc
     */
    public function setErrorCount(string $service = 'default', int $value = 0): void
    {
        apcu_store(
            sprintf('%s.%s.error_count', $this->prefix, $service),
            $value
        );
    }

    /**
     * @inheritdoc
     */
    public function getErrorCount(string $service = 'default'): int
    {
        return (int) apcu_fetch(
            sprintf('%s.%s.error_count', $this->prefix, $service)
        );
    }

    /**
     * @inheritdoc
     */
    public function getLastCheck(string $service = 'default'): int
    {
        return (int) apcu_fetch(
            sprintf('%s.%s.last_check', $this->prefix, $service)
        );
    }

    /**
     * @inheritdoc
     */
    public function updateLastCheck(string $service = 'default'): int
    {
        $time = time();
        apcu_store(
            sprintf('%s.%s.last_check', $this->prefix, $service),
            $time
        );

        return $time;
    }
}
