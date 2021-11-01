<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\RabbitMqAdapter;

use PhpAmqpLib\Message\AMQPMessage;
use Sebk\SmallEventsSwoft\Contract\SmallMessageBrokerMessageInterface;

class Message implements SmallMessageBrokerMessageInterface
{
    /** @var string */
    protected $content;

    /**
     * @param string $contents
     */
    public function __construct(string $contents)
    {
        $this->content = $contents;
    }

    /**
     * Convert message to rabbitmq format
     * @return AMQPMessage
     */
    public function getMessage()
    {
        return new AMQPMessage($this->content);
    }
}