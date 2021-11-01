<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\RabbitMqAdapter;

use Sebk\SmallEventsSwoft\Contract\SmallEventInterface;
use PhpAmqpLib\Message\AMQPMessage;

class Message
{
    /**
     * @var SmallEventInterface
     */
    protected $event;

    /**
     * Constructor
     * @param SmallEventInterface $event
     */
    public function __construct(SmallEventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * Convert message to rabbitmq format
     * @return AMQPMessage
     */
    public function getRabbitMqMessage(): AMQPMessage
    {
        return new AMQPMessage(json_encode($this->event));
    }
}