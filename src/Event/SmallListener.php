<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Event;

use Sebk\SmallEventsSwoft\Consumer\AbstractSmallConsumer;
use Sebk\SmallEventsSwoft\Event\Config;
use Sebk\SmallEventsSwoft\Contract\SmallConsumerInterface;
use Sebk\SmallEventsSwoft\Contract\SmallMessageBrokerInterface;
use Sebk\SmallEventsSwoft\Pool\SmallEventsPool;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * @Bean()
 */
class SmallListener
{
    /**
     * @var SmallEventsPool
     */
    protected $pool;

    /**
     * listen to a queue
     * If queue is not defined, listen to small events
     */
    public function listen(string $queue = null)
    {
        if ($queue == null) {
            $consumer = new SmallEventConsumer();
        } else {
            $consumer = $this->getConsumer($queue);
        }

        Bean(SmallMessageBrokerInterface::class)->listen(Bean('small_events.pool')->getConnection(), $consumer->getQueueName(), $consumer);
    }

    /**
     * Get consumer for queue
     * @param string $queue
     * @return AbstractSmallConsumer
     * @throws \Exception
     */
    public function getConsumer(string $queue)
    {
        /** @var AbstractSmallConsumer $consumer */
        foreach (config('small_events.consumers') as $consumer) {
            if ($consumer->getQueueName() == $queue) {
                return $consumer;
            }
        }

        throw new \Exception("No consumers for queue $queue !");
    }
}