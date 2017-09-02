<?php

namespace EventSourcing\Event;

abstract class DomainEvent
{
    protected $occurredOn;

    public function eventName()
    {
        $classNameParts = explode('\\', get_called_class());

        return array_pop($classNameParts);
    }

    public function occurredOn(): \DateTimeInterface
    {
        return $this->occurredOn;
    }

    abstract public function toArray(): array;

    abstract public static function fromArray(array $payload): DomainEvent;
}
