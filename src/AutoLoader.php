<?php declare(strict_types=1);

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft;

use Sebk\SmallEventsSwoft\Consumer\ConsumerCollection;
use Sebk\SmallEventsSwoft\Event\SmallDispatcher;
use Sebk\SmallEventsSwoft\Contract\SmallMessageBrokerInterface;
use Sebk\SmallEventsSwoft\Event\SmallListener;
use Sebk\SmallEventsSwoft\Pool\SmallEventsPool;
use Swoft\Helper\ComposerJSON;
use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            'smallDispatcher' => [
                'class' => SmallDispatcher::class,
                'option'   => [
                    'alias' => SmallDispatcher::class,
                ],
            ],
            'smallListener' => [
                'class' => SmallListener::class,
            ],
            'small_events.pool' => [
                'class'    => SmallEventsPool::class,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * Metadata information for the component.
     *
     * @return array
     * @see ComponentInterface::getMetadata()
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }
}
