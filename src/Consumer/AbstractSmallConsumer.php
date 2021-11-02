<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Consumer;

use Sebk\SmallEventsSwoft\Contract\SmallConsumerInterface;

abstract class AbstractSmallConsumer implements SmallConsumerInterface
{
    /**
     * @var string
     */
    protected $queueName;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        if (!isset($this->queueName)) {
            throw new \Exception('The queue name have not been set in constructor for consumer ' . static::class);
        }
    }

    /**
     * Get consumer queue name
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queueName;
    }
}