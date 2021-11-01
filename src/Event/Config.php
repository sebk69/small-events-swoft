<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsBundle\Event;

class Config
{
    const QUEUE_PREFIX = "smallEvents";

    public static function getSmallEventsQueueName()
    {
        return self::QUEUE_PREFIX . "." . config("smallEvents.applicationId");
    }
}