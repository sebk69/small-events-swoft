<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\RabbitMqAdapter;

class Config extends \Sebk\SmallEventsBundle\Event\Config
{
    /**
     * @var Exchange
     */
    protected $eventsExchange;

    /**
     * @var Queue
     */
    protected $eventsQueue;

    /**
     * Get events exchange
     * @return Exchange
     */
    public function getEventsExchange()
    {
        if ($this->eventsExchange === null) {
            $this->eventsExchange = new Exchange(static::QUEUE_PREFIX, 'fanout', false, true, false);
        }

        return $this->eventsExchange;
    }

    /**
     * Get events queue
     * @return Queue
     */
    public function getEventsQueue()
    {
        if ($this->eventsQueue == null) {
            $this->eventsQueue = new Queue(
                static::getSmallEventsQueueName(),
                $this->getEventsExchange(),
                false,
                true,
                false
            );
        }

        return $this->eventsQueue;
    }
}