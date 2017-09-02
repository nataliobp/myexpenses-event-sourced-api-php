<?php

namespace MyExpenses\Domain\Expense;

use EventSourcing\Event\DomainEvent;

class ExpenseWasRemoved extends DomainEvent
{
    private $expenseId;

    public function __construct(string $anExpenseId, \DateTimeInterface $occurredOn)
    {
        $this->expenseId = $anExpenseId;
        $this->occurredOn = $occurredOn;
    }

    public static function ofId(ExpenseId $anExpenseId): self
    {
        return new self(
            $anExpenseId->toString(),
            new \DateTime()
        );
    }
    public function toArray(): array
    {
        return [
            'expense_id' => $this->expenseId,
            'occurred_on' => $this->occurredOn->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $payload): DomainEvent
    {
        return new self(
            $payload['expense_id'],
            new \DateTime($payload['occurred_on'])
        );
    }

    public function expenseId(): string
    {
        return $this->expenseId;
    }
}
