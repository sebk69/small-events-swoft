<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsBundle\Consumer;

use Sebk\SmallEventsSwoft\Contract\SmallConsumerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * @Bean("smallConsumers")
 */
class ConsumerCollection
{
    /**
     * @var AbstractSmallConsumer[]
     */
    public $consumers = [];

    /**
     * Add a consumer
     * @param SmallConsumerInterface $consumer
     * @throws \Exception
     */
    public function register(SmallConsumerInterface $consumer): void
    {
        if (isset($this->consumers[$consumer->getQueueName()])) {
            throw new \Exception('A consumer has already been registered for queue ' . $consumer->getQueueName() . ' !');
        }

        $this->consumers[$consumer->getQueueName()] = $consumer;
    }

    /**
     *
     * @param string $queue
     * @return SmallConsumerInterface
     * @throws \Exception
     */
    public function get(string $queue): SmallConsumerInterface
    {
        if (isset($this->consumers[$queue])) {
            return $this->consumers[$queue];
        }

        throw new \Exception("Consumer for queue $queue not found !");
    }
}