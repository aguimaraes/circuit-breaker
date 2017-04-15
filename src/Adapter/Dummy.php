<?php
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
    public function setErrorCount($service = 'default', $value = 0)
    {
        $this->errorCount[$service] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getErrorCount($service = 'default')
    {
        return isset($this->errorCount[$service]) ? $this->errorCount[$service] : 0;
    }

    /**
     * @inheritdoc
     */
    public function getLastCheck($service = 'default')
    {
        return isset($this->lastCheck[$service]) ? $this->lastCheck[$service] : 0;
    }

    /**
     * @inheritdoc
     */
    public function updateLastCheck($service = 'default')
    {
        return $this->lastCheck[$service] = time();
    }
}
