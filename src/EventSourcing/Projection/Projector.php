<?php

namespace EventSourcing\Projection;

use EventSourcing\Event\DomainEvent;

interface Projector
{
    public function project(DomainEvent $anEvent): void;
}
