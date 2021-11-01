<?php

/**
 * This file is a part of sebk/small-events-swoft
 * Copyright 2021 - Sébastien Kus
 * Under GNU GPL V3 licence
 */

namespace Sebk\SmallEventsSwoft\RabbitMqAdapter;

use PhpAmqpLib\Channel\AMQPChannel;

class Exchange
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $type;

    /** @var bool */
    protected $passive;

    /**
     * @var bool
     */
    protected $durable;

    /**
     * @var bool
     */
    protected $autoDelete;

    /**
     * @var bool
     */
    protected $internal;

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
        string $type,
        bool $passive = false,
        bool $durable = true,
        bool $autoDelete = false,
        bool $internal = false,
        bool $nowait = false,
        array $arguments = [],
        int $ticket = null
    )
    {
        $this->name = $name;
        $this->type = $type;
        $this->passive = $passive;
        $this->durable = $durable;
        $this->autoDelete = $autoDelete;
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
     * @return Exchange
     */
    public function setName(string $name): Exchange
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Exchange
     */
    public function setType(string $type): Exchange
    {
        $this->type = $type;
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
     * @return Exchange
     */
    public function setPassive(bool $passive): Exchange
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
     * @return Exchange
     */
    public function setDurable(bool $durable): Exchange
    {
        $this->durable = $durable;
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
     * @return Exchange
     */
    public function setAutoDelete(bool $autoDelete): Exchange
    {
        $this->autoDelete = $autoDelete;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInternal(): bool
    {
        return $this->internal;
    }

    /**
     * @param bool $internal
     * @return Exchange
     */
    public function setInternal(bool $internal): Exchange
    {
        $this->internal = $internal;
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
     * @return Exchange
     */
    public function setNowait(bool $nowait): Exchange
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
     * @return Exchange
     */
    public function setArguments(array $arguments): Exchange
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @return int
     */
    public function getTicket(): int
    {
        return $this->ticket;
    }

    /**
     * @param int $ticket
     * @return Exchange
     */
    public function setTicket(int $ticket): Exchange
    {
        $this->ticket = $ticket;
        return $this;
    }

    /**
     * Declare exchange in channel
     * @param AMQPChannel $channel
     */
    public function declareExchange(AMQPChannel $channel)
    {
        $channel->exchange_declare(
            $this->name,
            $this->type,
            $this->passive,
            $this->durable,
            $this->autoDelete,
            $this->internal,
            $this->nowait,
            $this->arguments,
            $this->ticket,
        );
    }

}