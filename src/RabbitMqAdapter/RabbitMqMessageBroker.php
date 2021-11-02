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

    public function publish(SmallEventsConnectionInterface $connection, string $queue, $content)
    {
        // Check compatibility of connection
        if (!$connection instanceof RabbitMqConnection) {
            throw new \Exception('Connection must be instance of RabbitMqConnection !');
        }

        // Get channel
        /** @var AMQPChannel $channel */
        $channel = $connection->channel();

        $this->getQueue($queue)->declareQueue($channel);

        $channel->basic_publish((new Message(json_encode($content)))->getMessage(), $this->getQueue($queue)->getExchange()->getName());
    }

    /**
     * Listen to queue
     * @throws \ErrorException
     */
    public function listen(SmallEventsConnectionInterface $connection, $queue, SmallConsumerInterface $consumer)
    {
        // Check compatibility of connection
        if (!$connection instanceof RabbitMqConnection) {
            throw new \Exception('Connection must be instance of RabbitMqConnection !');
        }

        // Get channel
        /** @var AMQPChannel $channel */
        $channel = $connection->channel();

        // Declare exchange to channel
        $this->getQueue($queue)->declareQueue($channel);

        $channel->basic_consume($this->getQueue($queue)->getName(), config('small_events.applicationId'), false, false, false, false, [$consumer, 'consume']);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }

    /**
     * Get queue in params
     * @param string $queueName
     * @return Queue
     * @throws \Exception
     */
    public function getQueue(string $queueName): Queue
    {
        /** @var Queue $queue */
        foreach (config('small_events.rabbitMq.queues') as $queue) {
            if ($queue->getName() == $queueName) {
                return $queue;
            }
        }

        throw new \Exception("RabbitMq queue $queueName not defined !");
    }

}