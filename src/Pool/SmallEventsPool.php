<?php declare(strict_types=1);

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Pool;

use Sebk\SmallEventsSwoft\Contract\SmallEventsConnectionInterface;
use Swoft\Bean\BeanFactory;
use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\Contract\ConnectionInterface;

/**
 */
class SmallEventsPool extends AbstractPool
{

    /**
     * Default pool
     */
    public const DEFAULT_POOL = 'smallEvents.pool';


    /**
     * Create a new connection
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface
    {
        $connection = BeanFactory::getBean(SmallEventsConnectionInterface::class);
    }

    public function connection()
    {
        try {
            /* @var ConnectionManager $manager */
            $manager = BeanFactory::getBean(ConnectionManager::class);

            $connection = $this->getConnection();

            $manager->setRelease(true);
            $manager->setConnection($connection);
        } catch (\Throwable $e) {
            throw new \Exception(
                sprintf('Pool error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }

        // Not instanceof Connection
        if (!$connection instanceof ConnectionInterface) {
            throw new RabbitException(
                sprintf('%s is not instanceof %s', get_class($connection), ConnectionInterface::class)
            );
        }

        return $connection;
    }

}
