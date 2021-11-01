<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Event;

use Sebk\SmallEventsSwoft\Contract\SmallConsumerInterface;
use Swoft\Event\Event;

class SmallEventConsumer implements SmallConsumerInterface
{
    /**
     * @var string
     */
    protected $queueName;

    /**
     * Consumer
     * @param string $queueName
     */
    public function __construct(string $queueName)
    {
        $this->queueName = $queueName;
    }

    /**
     * Get consumer queue name
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queueName;
    }

    public function consume($messageContent): bool
    {
        // Get Swoft event manager
        $swoolEventManager = bean("eventManager");

        // decode message
        $data = json_decode($messageContent);

        // Create Swoft event
        $event = new Event($data["eventName"], $data["params"]);

        // Dispatch Swoft event
        $swoolEventManager->triggerEvent($event);

        // Ack message
        return true;
    }

}