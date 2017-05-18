<?php declare(strict_types=1);

namespace Aguimaraes\CircuitBreaker;

use Aguimaraes\Adapter\AdapterInterface;
use Aguimaraes\Adapter\Dummy;
use Aguimaraes\CircuitBreaker;
use Aguimaraes\Stats;
use League\StatsD\Client;

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
     * @var
     */
    protected $stats;

    /**
     * @param AdapterInterface|null $adapter
     * @param int $threshold
     * @param int $timeout
     */
    public function __construct(AdapterInterface $adapter = null, $threshold = 10, $timeout = 120)
    {
        $this->adapter = $adapter instanceof AdapterInterface ? $adapter : new Dummy();
        $this->threshold = $threshold;
        $this->timeout = $timeout;
    }

    /**
     * @param Client $stats
     */
    public function addStats(Client $stats)
    {
        $this->stats = $stats;
    }

    /**
     * @return CircuitBreaker
     */
    public function createCircuitBreaker()
    {
        $cb = new CircuitBreaker(
            $this->adapter,
            new Stats($this->stats)
        );
        $cb->setThreshold($this->threshold);
        $cb->setTimeout($this->timeout);

        return $cb;
    }
}
