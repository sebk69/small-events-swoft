<?php

namespace Sebk\SmallEventsSwoft\Traits;

use Sebk\SmallEventsBundle\Event\SmallDispatcher;
use Swoft\Bean\Annotation\Mapping\Inject;

trait SmallDispatcherTrait
{
    /**
     * @Inject()
     * @var SmallDispatcher
     */
    protected $smallDispatcher;
}