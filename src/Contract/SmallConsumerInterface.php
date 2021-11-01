<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Contract;

interface SmallConsumerInterface
{
    /**
     * Get consumer queue name
     * @return string
     */
    public function getQueueName(): string;

    /**
     * Consume message
     * Return true to ack message
     * @param $messageContent
     * @return bool
     */
    public function consume($messageContent): bool;
}