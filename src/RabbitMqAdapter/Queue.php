<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\RabbitMqAdapter;

use PhpAmqpLib\Channel\AMQPChannel;

class Queue
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Exchange
     */
    protected $exchange;

    /**
     * @var bool
     */
    protected $passive;

    /**
     * @var bool
     */
    protected $durable;

    /**
     * @var bool
     */
    protected $exclusive;

    /**
     * @var bool
     */
    protected $autoDelete;

    /**
     * @var bool
     */
    protected $nowait;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var int
     */
    protected $ticket;

    public function __construct(
        string $name,
        Exchange $exchange,
        bool $passive = false,
        bool $durable = false,
        bool $exclusive = false,
        bool $autoDelete = true,
        bool $nowait = false,
        array $arguments = [],
        int $ticket = null
    ) {
        $this->name = $name;
        $this->exchange = $exchange;
        $this->passive = $passive;
        $this->durable = $durable;
        $this->exclusive = $exclusive;
        $this->autoDelete = $autoDelete;
        $this->nowait = $nowait;
        $this->arguments = $arguments;
        $this->ticket = $ticket;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Queue
     */
    public function setName(string $name): Queue
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Exchange
     */
    public function getExchange(): Exchange
    {
        return $this->exchange;
    }

    /**
     * @param Exchange $exchange
     * @return Queue
     */
    public function setExchange(Exchange $exchange): Queue
    {
        $this->exchange = $exchange;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPassive(): bool
    {
        return $this->passive;
    }

    /**
     * @param bool $passive
     * @return Queue
     */
    public function setPassive(bool $passive): Queue
    {
        $this->passive = $passive;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDurable(): bool
    {
        return $this->durable;
    }

    /**
     * @param bool $durable
     * @return Queue
     */
    public function setDurable(bool $durable): Queue
    {
        $this->durable = $durable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isExclusive(): bool
    {
        return $this->exclusive;
    }

    /**
     * @param bool $exclusive
     * @return Queue
     */
    public function setExclusive(bool $exclusive): Queue
    {
        $this->exclusive = $exclusive;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoDelete(): bool
    {
        return $this->autoDelete;
    }

    /**
     * @param bool $autoDelete
     * @return Queue
     */
    public function setAutoDelete(bool $autoDelete): Queue
    {
        $this->autoDelete = $autoDelete;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNowait(): bool
    {
        return $this->nowait;
    }

    /**
     * @param bool $nowait
     * @return Queue
     */
    public function setNowait(bool $nowait): Queue
    {
        $this->nowait = $nowait;
        return $this;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     * @return Queue
     */
    public function setArguments(array $arguments): Queue
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @return int
     */
    public function getTicket(): ?int
    {
        return $this->ticket;
    }

    /**
     * @param int $ticket
     * @return Queue
     */
    public function setTicket(?int $ticket): Queue
    {
        $this->ticket = $ticket;
        return $this;
    }

    /**
     * Declare queue to channel
     * @param AMQPChannel $channel
     */
    public function declareQueue(AMQPChannel $channel)
    {
        $this->exchange->declareExchange($channel);
        $channel->queue_declare(
            $this->name,
            $this->passive,
            $this->durable,
            $this->exclusive,
            $this->autoDelete,
            $this->nowait,
            $this->arguments,
            $this->ticket
        );
        $channel->queue_bind($this->name, $this->exchange->getName());
    }
}