<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Contract;

interface SmallMessageBrokerMessageInterface
{
    public function __construct(string $content);
    public function getMessage();
}