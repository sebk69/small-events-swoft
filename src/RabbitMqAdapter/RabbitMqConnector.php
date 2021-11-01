<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\RabbitMqAdapter;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Connection\Pool\AbstractConnection;
use Swoft\Connection\Pool\Contract\ConnectionInterface;
use Sebk\SmallEventsSwoft\Pool\SmallEventsPool;

/**
 * @Bean()
 */
class RabbitMqConnector extends AbstractConnection implements ConnectionInterface
{

    public function initialize(SmallEventsPool $pool)
    {
        $this->pool = $pool;
        $this->id = $this->pool;
    }

    public function create(): void
    {
        // TODO: Implement create() method.
    }

    public function reconnect(): bool
    {
        // TODO: Implement reconnect() method.
    }

    public function getId(): int
    {
        // TODO: Implement getId() method.
    }

    public function release(bool $force = false): void
    {
        // TODO: Implement release() method.
    }

    public function getLastTime(): int
    {
        // TODO: Implement getLastTime() method.
    }

    public function updateLastTime(): void
    {
        // TODO: Implement updateLastTime() method.
    }

    public function setRelease(bool $release): void
    {
        // TODO: Implement setRelease() method.
    }

    public function setPoolName(string $poolName): void
    {
        // TODO: Implement setPoolName() method.
    }

    public function close(): void
    {
        // TODO: Implement close() method.
    }
}