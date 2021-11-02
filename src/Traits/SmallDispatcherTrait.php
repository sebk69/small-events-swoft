<?php

namespace Sebk\SmallEventsSwoft\Traits;

use Sebk\SmallEventsSwoft\Event\SmallDispatcher;
use Swoft\Bean\Annotation\Mapping\Inject;

trait SmallDispatcherTrait
{
    /**
     * @Inject()
     * @var SmallDispatcher
     */
    protected $smallDispatcher;
}