<?php declare(strict_types=1);

namespace Aguimaraes;

use League\StatsD\Client;

class Stats
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $service
     */
    public function available($service = 'default')
    {
        if (empty($client)) {
            return;
        }
        $this->client->increment("circuit-breaker.$service.available");
    }

    /**
     * @param string $service
     */
    public function notAvailable($service = 'default')
    {
        if (empty($client)) {
            return;
        }
        $this->client->increment("circuit-breaker.$service.not-available");
    }

    /**
     * @param string $service
     */
    public function failure($service = 'default')
    {
        if (empty($client)) {
            return;
        }
        $this->client->increment("circuit-breaker.$service.failure_reported");
    }

    /**
     * @param string $service
     */
    public function success($service = 'default')
    {
        if (empty($client)) {
            return;
        }
        $this->client->increment("circuit-breaker.$service.success_reported");
    }

}
