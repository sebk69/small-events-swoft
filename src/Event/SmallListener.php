<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Event;

use Sebk\SmallEventsBundle\Event\Config;
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
    public function listen($queue = null)
    {
        if ($queue == null) {
            $consumer = new SmallEventConsumer();
        } else {
            $consumer = bean("smallConsumers")->get($queue);
        }

        Bean(SmallMessageBrokerInterface::class)->listen($this->pool->getConnection(), $consumer->getQueueName(), $consumer);
    }
}