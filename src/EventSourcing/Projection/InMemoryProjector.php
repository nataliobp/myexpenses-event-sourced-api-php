<?php

namespace EventSourcing\Projection;

use EventSourcing\Event\DomainEvent;

class InMemoryProjector implements Projector
{
    private $views = [];

    public function project(DomainEvent $anEvent): void
    {
        $methodName = 'when'.$anEvent->eventName();
        $this->$methodName($anEvent);
    }

    protected function views($viewName): \ArrayObject
    {
        if (!isset($this->views[$viewName])) {
            $this->views[$viewName] = new \ArrayObject();
        }

        return $this->views[$viewName];
    }
}
