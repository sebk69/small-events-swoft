<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\RabbitMqAdapter;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Sebk\SmallEventsSwoft\Contract\SmallEventsConnectionInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Connection\Pool\AbstractConnection;
use Swoft\Connection\Pool\Contract\ConnectionInterface;
use Sebk\SmallEventsSwoft\Pool\SmallEventsPool;

/**
 * @Bean()
 */
class RabbitMqConnection extends AbstractConnection implements SmallEventsConnectionInterface, ConnectionInterface
{
    /**
     * @var AMQPStreamConnection
     */
    protected $client;

    /**
     * Initialize service
     * @param SmallEventsPool $pool
     */
    public function initialize(SmallEventsPool $pool)
    {
        $this->pool = $pool;
        $this->lastTime = time();
        $this->id = $this->pool;
    }

    /**
     * Create a new connection
     */
    public function create(): void
    {
        $this->client = new AMQPStreamConnection(
            config('small_events.rabbitMq.host', 'localhost'),
            config('small_events.rabbitMq.port', 5672),
            config('small_events.rabbitMq.user', 'guest'),
            config('small_events.rabbitMq.password', 'guest'),
            config('small_events.rabbitMq.vhost', '/'),
        );
    }

    /**
     * Reconnect
     * @return bool
     */
    public function reconnect(): bool
    {
        $this->create();
    }

    /**
     * Close connection
     * @throws \Exception
     */
    public function close(): void
    {
        $this->client->close();
    }

    /**
     * Redirect to client command
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    public function __call(string $method, array $parameters)
    {
        return $this->command($method, $parameters);
    }

    /**
     * Manage command redirect to client
     * @param string $method
     * @param array $parameters
     * @param bool $reconnect
     * @return mixed
     * @throws \Exception
     */
    public function command(string $method, array $parameters = [], bool $reconnect = false)
    {
        try {
            if (!method_exists($this->client, $method)) {
                throw new \Exception("RabbitMq does not support $method !");
            }
            $result = $this->client->$method(...$parameters);

        } catch (\Throwable $e) {
            if (!$reconnect && $this->reconnect()) {
                return $this->command($method, $parameters, true);
            }

            throw new \Exception('RabbitMq command reconnect error=' . $e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }

    /**
     * Release connection
     * @param bool $force
     */
    public function release(bool $force = false): void
    {
        $manager = BeanFactory::getBean(ConnectionManager::class);
        $manager->releaseConnection($this->id);

        parent::release($force);
    }

}