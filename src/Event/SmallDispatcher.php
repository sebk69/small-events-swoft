<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Event;

use Sebk\SmallEventsSwoft\Contract\SmallEventInterface;
use Sebk\SmallEventsSwoft\Contract\SmallMessageBrokerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * @Bean()
 */
class SmallDispatcher
{

    /**
     * Dispatch event to message broker
     * @param SmallEventInterface $event
     */
    public function dispatch(SmallEventInterface $event): void
    {
        // Create data of message
        $content = [
            'from' => config('small_events.applicationId'),
            'eventName' => $event->getName(),
            'params' => $event->getParams(),
        ];

        // Send the message
        $this->sendMessage(Config::EVENT_EXCHANGE . '.' . config("small_events.applicationId"), $content);

        // TODO Log event sent
    }

    /**
     * Send a message to queue
     * $message must be a json serializable format
     * @param string $queue
     * @param mixed $content
     */
    public function sendMessage(string $queue, $message)
    {
        // Send message
        Bean(SmallMessageBrokerInterface::class)->publish(Bean('small_events.pool')->getConnection(), $queue, $message);
    }

}