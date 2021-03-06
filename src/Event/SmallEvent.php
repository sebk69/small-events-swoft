<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Event;

use Swoft\Event\Event;
use Sebk\SmallEventsSwoft\Contract\SmallEventInterface;

/**
 * Class SmallEvent
 */
class SmallEvent extends Event implements SmallEventInterface, \JsonSerializable
{
    /**
     * Json serialization (For publish via message broker : Json is interpretable by any microservice in other languages)
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'eventName' => $this->getName(),
            'params' => $this->params,
        ];
    }
}
