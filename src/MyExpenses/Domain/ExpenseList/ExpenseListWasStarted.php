<?php

namespace MyExpenses\Domain\ExpenseList;

use EventSourcing\Event\DomainEvent;

class ExpenseListWasStarted extends DomainEvent
{
    private $expenseListId;
    private $name;

    public function __construct(
        string $anExpenselIstId,
        string $name,
        \DateTime $occurredOn
    ) {
        $this->expenseListId = $anExpenselIstId;
        $this->name = $name;
        $this->occurredOn = $occurredOn;
    }

    public static function withData(ExpenseListId $expenseListId, string $name): self
    {
        return new self(
            $expenseListId->toString(),
            $name,
            new \DateTime()
        );
    }

    public function expenseListId()
    {
        return $this->expenseListId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'expense_list_id' => $this->expenseListId,
            'name' => $this->name,
            'occurred_on' => $this->occurredOn->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $payload): DomainEvent
    {
        return new self(
            (string) $payload['expense_list_id'],
            (string) $payload['name'],
            new \DateTime($payload['occurred_on'])
        );
    }
}
