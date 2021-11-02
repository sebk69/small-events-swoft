<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Consumer;

use Sebk\SmallEventsSwoft\Event\Config;
use Swoft\Event\Event;

class SmallEventConsumer extends AbstractSmallConsumer
{

    /**
     * Constructor
     * @throws \Exception
     */
    public function __construct()
    {
        $this->queueName = Config::EVENT_EXCHANGE . '.' . config("small_events.applicationId");

        parent::__construct();
    }

    /**
     * Consume message
     * @param $messageContent
     * @return bool
     */
    public function consume($messageContent): bool
    {
        // decode message
        $data = json_decode($messageContent, true);

        // Create Swoft event
        $event = new Event($data['eventName'], $data['params']);

        // Dispatch Swoft event
        \Swoft::trigger($event->getName(), $event->getTarget(), $data['params']);

        // Ack message
        return true;
    }

}