<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Contract;

interface SmallEventInterface
{
    public function __construct(string $name, array $params);
    public function getName();
    public function getParams();
    public function jsonSerialize();
}