# sebk/small-events-swoft

Swoft cross applications events dispatcher and receiver

## Install

Create your Swoft project : http://swoft.io/docs/2.x/en/quick-start/install.html

Require package (https://github.com/sebk69/small-events-swoft) :
```
composer require sebk/small-events-swoft
```

## Documentation

### Supported connectors

For now, only RabbitMq is supported.

In future, Apache Kafka and MySql connectors are planned.

### Configuration

Add contract to choose your massage brocker to your 'bean.php' :
```
return [
    ...
    // Small events contracts
    \Sebk\SmallEventsSwoft\Contract\SmallMessageBrokerInterface::class => [
        'class' => \Sebk\SmallEventsSwoft\RabbitMqAdapter\RabbitMqMessageBroker::class,
    ],
    ...
];
```

Here RabbitMq message broker will be used.

Create a 'small_events.php' file in your config dir :
```
<?php

use Sebk\SmallEventsSwoft\RabbitMqAdapter\Exchange;
use Sebk\SmallEventsSwoft\RabbitMqAdapter\Queue;
use Sebk\SmallEventsSwoft\Event\Config;

return [
    'applicationId' => 'myApp',
    'rabbitMq' => [
        'host' => 'localhost',
        'user' => 'guest',
        'password' => 'guest',
        'smallEventsExchange' => new Exchange(Config::EVENT_EXCHANGE, 'fanout'),
        'queues' => [
            new Queue(Config::EVENT_EXCHANGE . '.myApp', new Exchange(Config::EVENT_EXCHANGE, 'fanout'), false, true, false),
            new Queue('test', new Exchange('test', 'topic', false, true, false)),
        ],
    ],
    'consumers' => [
        new \App\Consumer\TestConsumer(),
    ]
];
```

The applicationId must be unique between all your applications : it will be used to create a queue to receive events.

In the RabbitMq section, you can set server host, user and password.

The smallEventsExchange is the exchane that will be used to dispatch events in all your applications.

In the queues array, declare all queues that you will use for events or messaging. The first queue is dedicated to your events flow and must be declared :
* The first parameter must be ['Sebk\SmallEventsSwoft\Event\Config::EVENT_EXCHANGE'][dot][your applicationId]
* The second one is the definition of events exchange, must be defined as the 'smallEventsExchange' parameter

The second queue can be used to send messages outside event flow.

The consumers array must contain the list of consumers objects, used to listen all queues outside events queue (see below for how to create consumer class).

### Send a message outside event flow

Their is a simple method to send messages to other applications :
```
bean('smallDispatcher')->sendMessage('test', $message);
```

This command will send $message to the exchange of 'test' queue.

### Send an event

The class SmallEvent, allow you to create an event that will be shared with all listening applications :
```
$event = new SmallEvent('testEvent', ['subject' => $test]);
```

The first parameter is the event name and the second parameter is the data of your event.

Note that the data must be json serializable.

Then use the dispatcher to send event to other applications (including yours) :
```
bean('smallDispatcher')->dispatch($event);
```

### Create consumers

The simple messages (not event one) can be consumed by creating consumers (the ones we have seen in configuation section)

Here is an example :
```
<?php

namespace App\Consumer;

use Sebk\SmallEventsSwoft\Consumer\AbstractSmallConsumer;

class TestConsumer extends AbstractSmallConsumer
{

    public function __construct()
    {
        $this->queueName = 'test';

        parent::__construct();
    }

    public function consume($messageContent): bool
    {
        var_dump($messageContent);

        return true;
    }

}
```

A consumer must extends 'AbstractSmallConsumer' class and define the queue name to listen in constructor.

The method 'consume' will be called at every time a message is received from the queue. It must return true to ack message.

### Listen to a queue

You must use the following command to listen a queue :
```
$ bin/swoft small-events:listen --queue test
```

This command will wait for messages of teh queue 'test' and call your consumer class at each message.

If you don't use --queue parameter, the proccess will listen to events.

To manage your workers, you can use tools like Supervisor : http://supervisord.org

### And events ? How to subscribe

Events are dispatched via Swoft/Event standard package (http://swoft.io/docs/2.x/en/event/usage.html).

Here is a small example to subscibe to our 'testEvent' :
```
<?php

namespace App\Event;

use Swoft\Event\Annotation\Mapping\Subscriber;
use Swoft\Event\EventInterface;
use Swoft\Event\EventSubscriberInterface;

/**
 * @Subscriber()
 */
class TestSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            'testEvent' => 'handleTest',
        ];
    }

    public function handleTest(EventInterface $event)
    {
        dump($event->getParams());
    }

}
```