<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Event;

use Sebk\SmallEventsBundle\Consumer\AbstractSmallConsumer;
use Swoft\Event\Event;

class SmallEventConsumer extends AbstractSmallConsumer
{

    /**
     * Constructor
     * @throws \Exception
     */
    public function __construct()
    {
        $this->queueName = Config::EVENT_EXCHANGE . config("small_events.applicationId");

        parent::__construct();
    }

    /**
     * Consume message
     * @param $messageContent
     * @return bool
     */
    public function consume($messageContent): bool
    {
        // Get Swoft event manager
        $swoftEventManager = bean('eventManager');

        // decode message
        $data = json_decode($messageContent);

        // Create Swoft event
        $event = new Event($data['eventName'], $data['params']);

        // Dispatch Swoft event
        $swoftEventManager->triggerEvent($event);

        // Ack message
        return true;
    }

}