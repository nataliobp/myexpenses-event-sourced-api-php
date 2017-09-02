<?php

namespace EventSourcing\EventStore;

use EventSourcing\Aggregate\Aggregate;
use EventSourcing\Event\DomainEvent;
use EventSourcing\Event\EventSerializer;
use EventSourcing\Stream\StreamName;

class FileEventStore implements EventStore
{
    private $serializer;
    private $filename;

    public function __construct($filename, EventSerializer $serializer)
    {
        $this->serializer = $serializer;
        $this->filename = $filename;
    }

    public function append(DomainEvent $anEvent, StreamName $aStreamName): void
    {
        file_put_contents(
            $this->filename,
            json_encode([$aStreamName->toString() => $this->serializer->serialize($anEvent)]),
            FILE_APPEND
        );
    }

    /**
     * @param Aggregate $anAggregate
     *
     * @return Aggregate
     */
    public function reconstituteAggregate($anAggregate)
    {
        array_map(
            function (string $anEventWrapperPayload) use ($anAggregate) {
                $anEventWrapper = json_decode($anEventWrapperPayload, true);

                if (StreamName::fromString(key($anEventWrapper))->belongsToAggregate($anAggregate)) {
                    $anEvent = $this->serializer->deserialize($anEventWrapper);
                    $anAggregate->apply($anEvent);
                }
            },
            file($this->filename)
        );

        return $anAggregate;
    }
}
