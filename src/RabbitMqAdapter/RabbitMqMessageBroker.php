<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\RabbitMqAdapter;

use PhpAmqpLib\Channel\AMQPChannel;
use Sebk\SmallEventsSwoft\Contract\SmallConsumerInterface;
use Sebk\SmallEventsSwoft\Contract\SmallEventsConnectionInterface;
use Sebk\SmallEventsSwoft\Pool\SmallEventsPool;

class RabbitMqMessageBroker
{

    /**
     * @var Queue[]
     */
    protected $queues = [];

    public function __construct()
    {
        $this->config = bean("smallEvents.rabbitMqConfig");
    }

    /**
     * Add a queue
     * @param Queue $queue
     */
    public function addQueue(Queue $queue)
    {
        $this->queues[$queue->getName()] = $queue;
    }

    /**
     * Listen to queue
     * @throws \ErrorException
     */
    public function listen(RabbitMqConnection $connection, $queue, SmallConsumerInterface $consumer)
    {
        // Get channel
        /** @var AMQPChannel $channel */
        $channel = $connection->channel();

        // Declare exchange to channel
        /** @var Exchange $exchange */
        $this->queues[$queue]->declareQueue($channel);

        $channel->basic_consume($queue, config(""), false, false, false, false, [$this, "consume"]);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

}