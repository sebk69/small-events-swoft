<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsBundle\Event;

use Sebk\SmallEventsSwoft\Contract\SmallEventInterface;
use Sebk\SmallEventsSwoft\Contract\SmallMessageBrokerInterface;
use Sebk\SmallEventsSwoft\Contract\SmallMessageBrokerMessageInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Event\Manager\EventManagerInterface;

/**
 * @Bean()
 */
class SmallDispatcher
{
    /**
     * @var SmallMessageBrokerInterface
     */
    protected $messageBroker;

    /**
     * @var EventManagerInterface
     */
    protected $swoftEventManager;

    /**
     * @var string
     */
    protected $applicationId;

    /**
     * @var string
     */
    protected $eventQueue;

    /**
     * SmallDispatcher constructor.
     * @param SmallMessageBroker $messageBroker
     * @param EventManagerInterface $local
     */
    public function __construct(SmallMessageBrokerInterface $messageBroker, EventManagerInterface $swoftEventManager)
    {
        $this->messageBroker = $messageBroker;
        $this->swoftEventManager = $swoftEventManager;
        $this->applicationId = config("smallEvents.applicationId");
        $this->eventQueue = config("smallEvents.eventQueue");
    }

    /**
     * Dispatch event to message broker
     * @param SmallEventInterface $event
     */
    public function dispatch(SmallEventInterface $event): void
    {
        // Create data of message
        $content = [
            "from" => $this->applicationId,
            "eventName" => $event->getName(),
            "params" => $event->getParams(),
        ];

        // Send the message
        $this->sendMessage($this->eventQueue, $content);

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
        $this->messageBroker->publish($queue, $message);
    }

    /**
     * Publish a message
     * @param string $queue
     * @param SmallMessageBrokerMessageInterface $message
     */
    protected function publishMessage(string $queue, SmallMessageBrokerMessageInterface $message)
    {
        $this->messageBroker->publish($queue, $message->getMessage());
    }

}