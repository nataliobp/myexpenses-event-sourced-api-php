<?php

namespace MyExpenses\Domain\Expense;

use EventSourcing\Event\DomainEvent;

class ExpenseWasAdded extends DomainEvent
{
    private $expenseId;
    private $amount;
    private $categoryId;
    private $spenderId;
    private $description;
    private $expenseListId;

    public function __construct(
        string $anExpenseId,
        int $anAmount,
        string $aCategoryId,
        string $aSpenderId,
        string $aDescription,
        string $anExpenseListId,
        \DateTimeInterface $occurredOn
    ) {
        $this->expenseId = $anExpenseId;
        $this->amount = $anAmount;
        $this->categoryId = $aCategoryId;
        $this->spenderId = $aSpenderId;
        $this->description = $aDescription;
        $this->expenseListId = $anExpenseListId;
        $this->occurredOn = $occurredOn;
    }

    public static function withData(
        string $anExpenseId,
        int $anAmount,
        string $aCategoryId,
        string $aSpenderId,
        string $aDescription,
        string $anExpenseListId
    ): self {
        return new self(
            $anExpenseId,
            $anAmount,
            $aCategoryId,
            $aSpenderId,
            $aDescription,
            $anExpenseListId,
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

    public function spenderId(): string
    {
        return $this->spenderId;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function expenseListId(): string
    {
        return $this->expenseListId;
    }

    public function toArray(): array
    {
        return [
            'expense_id' => $this->expenseId(),
            'amount' => $this->amount(),
            'category_id' => $this->categoryId(),
            'spender_id' => $this->spenderId(),
            'description' => $this->description(),
            'expense_list_id' => $this->expenseListId(),
            'occurred_on' => $this->occurredOn()->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $payload): DomainEvent
    {
        return new self(
            (string) $payload['expense_id'],
            (int) $payload['amount'],
            (string) $payload['category_id'],
            (string) $payload['spender_id'],
            (string) $payload['description'],
            (string) $payload['expense_list_id'],
            new \DateTime($payload['occurred_on'])
        );
    }
}
