<?php

namespace MyExpenses\Domain\Expense;

use EventSourcing\Event\DomainEvent;

class ExpenseWasAltered extends DomainEvent
{
    private $expenseId;
    private $amount;
    private $categoryId;
    private $description;

    public function __construct(
        string $anExpenseId,
        int $anAmount,
        string $aCategoryId,
        string $aDescription,
        \DateTimeInterface $occurredOn
    ) {
        $this->expenseId = $anExpenseId;
        $this->amount = $anAmount;
        $this->categoryId = $aCategoryId;
        $this->description = $aDescription;
        $this->occurredOn = $occurredOn;
    }

    public static function withData(
        string $anExpenseId,
        int $anAmount,
        string $aCategoryId,
        string $aDescription
    ): self {
        return new self(
            $anExpenseId,
            $anAmount,
            $aCategoryId,
            $aDescription,
            new \DateTime()
        );
    }

    public function expenseId(): string
    {
        return $this->expenseId;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return [
            'expense_id' => $this->expenseId(),
            'amount' => $this->amount(),
            'category_id' => $this->categoryId(),
            'description' => $this->description(),
            'occurred_on' => $this->occurredOn()->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $payload): DomainEvent
    {
        return new self(
            (string) $payload['expense_id'],
            (int) $payload['amount'],
            (string) $payload['category_id'],
            (string) $payload['description'],
            new \DateTime($payload['occurred_on'])
        );
    }
}
