<?php

namespace EventSourcing\EventListener\Emitter;

use EventSourcing\Event\DomainEvent;
use EventSourcing\Projection\Projector;
use League\Event\AbstractListener;
use League\Event\EventInterface;

class EmitterProjectorListener extends AbstractListener
{
    private $projector;

    public function __construct(Projector $projector)
    {
        $this->projector = $projector;
    }

    public function handle(EventInterface $emitterEvent, DomainEvent $aDomainEvent = null)
    {
        $this->projector->project($aDomainEvent);
    }
}
