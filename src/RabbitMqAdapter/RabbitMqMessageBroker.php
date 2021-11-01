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
use Sebk\SmallEventsSwoft\Contract\SmallMessageBrokerInterface;
use Sebk\SmallEventsSwoft\Pool\SmallEventsPool;

class RabbitMqMessageBroker implements SmallMessageBrokerInterface
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

    public function publish(SmallEventsConnectionInterface $connection, string $queue, $content)
    {
        // Check compatibility of connection
        if (!$connection instanceof RabbitMqConnection) {
            throw new \Exception("Connection must be instance of RabbitMqConnection !");
        }

        // Get channel
        /** @var AMQPChannel $channel */
        $channel = $connection->channel();

        $channel->basic_publish((new Message(json_encode($content)))->getMessage(), $this->queues[$queue]->getExchange()->getName());
    }

    /**
     * Listen to queue
     * @throws \ErrorException
     */
    public function listen(SmallEventsConnectionInterface $connection, $queue, SmallConsumerInterface $consumer)
    {
        // Check compatibility of connection
        if (!$connection instanceof RabbitMqConnection) {
            throw new \Exception("Connection must be instance of RabbitMqConnection !");
        }

        // Get channel
        /** @var AMQPChannel $channel */
        $channel = $connection->channel();

        // Declare exchange to channel
        /** @var Exchange $exchange */
        $this->queues[$queue]->declareQueue($channel);

        $channel->basic_consume($queue, config("smallEvents.applicationId"), false, false, false, false, [$consumer, "consume"]);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

}