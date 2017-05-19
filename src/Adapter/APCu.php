<?php

namespace Aguimaraes\Adapter;

class APCu implements AdapterInterface
{
    private $prefix = 'circuit_breaker';

    /**
     * @inheritdoc
     */
    public function setErrorCount($service = 'default', $value = 0)
    {
        apcu_store(
            sprintf('%s.%s.error_count', $this->prefix, $service),
            $value
        );
    }

    /**
     * @inheritdoc
     */
    public function getErrorCount($service = 'default')
    {
        return (int)apcu_fetch(
            sprintf('%s.%s.error_count', $this->prefix, $service)
        );
    }

    /**
     * @inheritdoc
     */
    public function getLastCheck($service = 'default')
    {
        return (int)apcu_fetch(
            sprintf('%s.%s.last_check', $this->prefix, $service)
        );
    }

    /**
     * @inheritdoc
     */
    public function updateLastCheck($service = 'default')
    {
        $time = time();
        apcu_store(
            sprintf('%s.%s.last_check', $this->prefix, $service),
            $time
        );

        return $time;
    }
}
