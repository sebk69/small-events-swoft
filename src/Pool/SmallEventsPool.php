<?php declare(strict_types=1);

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\Pool;

use Swoft\Bean\BeanFactory;
use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\Contract\ConnectionInterface;

/**
 */
class SmallEventsPool extends AbstractPool
{
    
    /**
     * Default pool
     */
    public const DEFAULT_POOL = 'small-events.pool';

}
