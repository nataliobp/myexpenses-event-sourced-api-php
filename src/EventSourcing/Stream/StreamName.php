<?php

namespace EventSourcing\Stream;

use EventSourcing\Aggregate\Aggregate;

class StreamName
{
    private $aggregateName;
    private $aggregateId;

    public function __construct($aggregateName, $aggregateId)
    {
        $this->aggregateName = $aggregateName;
        $this->aggregateId = $aggregateId;
    }

    public static function fromAggregate(Aggregate $anAgregate)
    {
        return new self(get_class($anAgregate), $anAgregate->id()->toString());
    }

    public static function fromString(string $streamName): self
    {
        [$aggregateName, $aggregateId] = explode('_', $streamName);

        return new self($aggregateName, $aggregateId);
    }

    public function __toString()
    {
        return sprintf('%s_%s', $this->aggregateName, $this->aggregateId);
    }

    public function toString()
    {
        return (string) $this;
    }

    public function belongsToAggregate($aggregateName): bool
    {
        return $this->aggregateName === $aggregateName;
    }
}
