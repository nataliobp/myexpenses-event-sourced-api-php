<?php

namespace EventSourcing\Aggregate;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AggregateId implements AggregateRootId
{
    private $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function create(): self
    {
        return new static(Uuid::uuid4());
    }

    public static function ofId(string $id): AggregateRootId
    {
        return new static(Uuid::fromString($id));
    }

    public function toString(): string
    {
        return $this->id->toString();
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }

    public function equals(AggregateId $id): bool
    {
        return $id->toString() === $this->id->toString();
    }
}
