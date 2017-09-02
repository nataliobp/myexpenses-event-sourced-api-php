<?php

namespace MyExpenses\Domain\Category;

use EventSourcing\Event\DomainEvent;
use MyExpenses\Domain\ExpenseList\ExpenseListId;

class CategoryWasCreated extends DomainEvent
{
    private $name;
    private $expenseListId;
    private $categoryId;

    public function __construct(
        string $aCategoryId,
        string $name,
        string $expenseListId,
        \DateTime $occurredOn
    ) {
        $this->categoryId = $aCategoryId;
        $this->name = $name;
        $this->expenseListId = $expenseListId;
        $this->occurredOn = $occurredOn;
    }

    public static function withData(
        CategoryId $categoryId,
        string $name,
        ExpenseListId $expenseListId
    ): self {
        return new self(
            $categoryId->toString(),
            $name,
            $expenseListId->toString(),
            new \DateTime()
        );
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function expenseListId()
    {
        return $this->expenseListId;
    }

    public function toArray(): array
    {
        return [
            'category_id' => $this->categoryId,
            'name' => $this->name,
            'expense_list_id' => $this->expenseListId,
            'occurred_on' => $this->occurredOn->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $payload): DomainEvent
    {
        return new self(
            (string) $payload['category_id'],
            (string) $payload['name'],
            (string) $payload['expense_list_id'],
            new \DateTime($payload['occurred_on'])
        );
    }
}
