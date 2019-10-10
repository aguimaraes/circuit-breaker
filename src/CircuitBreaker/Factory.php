<?php declare(strict_types=1);

namespace Aguimaraes\CircuitBreaker;

use Aguimaraes\Adapter\AdapterInterface;
use Aguimaraes\Adapter\Dummy;
use Aguimaraes\CircuitBreaker;

class Factory
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var integer
     */
    protected $threshold;

    /**
     * @var integer
     */
    protected $timeout;

    /**
     * @param AdapterInterface|null $adapter
     * @param int $threshold
     * @param int $timeout
     */
    public function __construct(AdapterInterface $adapter = null, int $threshold = 10, int $timeout = 120)
    {
        $this->adapter = $adapter instanceof AdapterInterface ? $adapter : new Dummy();
        $this->threshold = $threshold;
        $this->timeout = $timeout;
    }

    /**
     * @return CircuitBreaker
     */
    public function createCircuitBreaker(): CircuitBreaker
    {
        $cb = new CircuitBreaker($this->adapter);
        $cb->setThreshold($this->threshold);
        $cb->setTimeout($this->timeout);

        return $cb;
    }
}
