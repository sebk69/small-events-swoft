<?php declare(strict_types=1);

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Pool;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Co;
use Swoft\Concern\ArrayPropertyTrait;
use Swoft\Connection\Pool\Contract\ConnectionInterface;

/**
 * @Bean()
 */
class ConnectionManager
{
    use ArrayPropertyTrait;

    /**
     * Set connection
     * @param ConnectionInterface $connection
     */
    public function setConnection(ConnectionInterface $connection): void
    {
        $key = sprintf('%d.%d.%d', Co::tid(), Co::id(), $connection->getId());
        $this->set($key, $connection);
    }

    /**
     * Release connection
     * @param int $id
     */
    public function releaseConnection(int $id): void
    {
        $key = sprintf('%d.%d.%d', Co::tid(), Co::id(), $id);

        $this->unset($key);
    }

    /**
     * Release all connections
     * @param bool $final
     */
    public function release(bool $final = false): void
    {
        $key = sprintf('%d.%d', Co::tid(), Co::id());

        $connections = $this->get($key, []);
        foreach ($connections as $connection) {
            if ($connection instanceof ConnectionInterface) {
                $connection->release();
            }
        }

        if ($final) {
            $finalKey = sprintf('%d', Co::tid());
            $this->unset($finalKey);
        }
    }

}
