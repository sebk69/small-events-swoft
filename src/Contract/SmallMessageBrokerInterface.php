<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Contract;

interface SmallMessageBrokerInterface
{
    public function publish(SmallEventsConnectionInterface $connection, string $queue, $content);
    public function listen(SmallEventsConnectionInterface $connection, string $queue, SmallConsumerInterface $consumer);
}