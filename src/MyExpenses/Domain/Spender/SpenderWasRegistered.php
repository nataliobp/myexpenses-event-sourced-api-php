<?php

namespace MyExpenses\Domain\Spender;

use EventSourcing\Event\DomainEvent;

class SpenderWasRegistered extends DomainEvent
{
    private $name;
    private $spenderId;

    public function __construct(
        string $aSpenderId,
        string $name,
        \DateTime $occurredOn
    ) {
        $this->spenderId = $aSpenderId;
        $this->name = $name;
        $this->occurredOn = $occurredOn;
    }

    public static function withData(SpenderId $spenderId, string $name): self
    {
        return new self(
            $spenderId->toString(),
            $name,
            new \DateTime()
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function spenderId()
    {
        return $this->spenderId;
    }

    public function toArray(): array
    {
        return [
            'spender_id' => $this->spenderId,
            'name' => $this->name,
            'occurred_on' => $this->occurredOn->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $payload): DomainEvent
    {
        return new self(
            (string) $payload['spender_id'],
            (string) $payload['name'],
            new \DateTime($payload['occurred_on'])
        );
    }
}
