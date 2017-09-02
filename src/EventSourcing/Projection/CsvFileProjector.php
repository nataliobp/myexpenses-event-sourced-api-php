<?php

namespace EventSourcing\Projection;

use EventSourcing\Event\DomainEvent;

abstract class CsvFileProjector implements Projector
{
    protected $file;
    protected $fields;

    public function __construct()
    {
        if (!file_exists($this->filename())) {
            file_put_contents($this->filename(), $this->csvHeaders());
        }

        $this->file = fopen($this->filename(), 'a');
    }

    public function project(DomainEvent $anEvent): void
    {
        $methodName = 'when'.$anEvent->eventName();

        if (method_exists($this, $methodName)) {
            $this->$methodName($anEvent);
        }
    }

    private function csvHeaders(): string
    {
        return implode(',', $this->fields())."\n";
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    abstract protected function fields(): array;

    abstract protected function filename(): string;
}
