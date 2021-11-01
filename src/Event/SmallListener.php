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
     * @var SmallConsumerInterface[]
     */
    protected $consumers = [];

    /**
     * @var SmallMessageBrokerInterface
     */
    protected $messageBroker;

    /**
     * Add a consumer
     * @param string $queue
     * @param SmallConsumerInterface $consumer
     */
    public function addConsumer(SmallConsumerInterface $consumer)
    {
        $this->consumers[$consumer->getQueueName()] = $consumer;
    }

    /**
     * listen to a queue
     * If queue is not defined, listen to small events
     */
    public function listen($queue = null)
    {
        if ($queue == null) {
            $consumer = new SmallEventConsumer(Config::getSmallEventsQueueName());
        } else {
            $consumer = $this->consumers[$queue];
        }

        $this->messageBroker->listen($this->pool->getConnection(), $consumer->getQueueName(), $consumer);
    }
}