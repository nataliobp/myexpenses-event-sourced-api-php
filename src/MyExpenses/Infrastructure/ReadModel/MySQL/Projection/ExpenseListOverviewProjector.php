<?php

namespace MyExpenses\Infrastructure\ReadModel\MySQL\Projection;

use EventSourcing\Event\EventSerializer;
use EventSourcing\Projection\MySQLProjector;
use MyExpenses\Domain\Category\CategoryId;
use MyExpenses\Domain\Category\CategoryRepository;
use MyExpenses\Domain\Expense\ExpenseId;
use MyExpenses\Domain\Expense\ExpenseRepository;
use MyExpenses\Domain\Expense\ExpenseWasAdded;
use MyExpenses\Domain\Expense\ExpenseWasAltered;
use MyExpenses\Domain\Expense\ExpenseWasRemoved;
use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Domain\ExpenseList\ExpenseListWasStarted;
use MyExpenses\Domain\Spender\SpenderId;
use MyExpenses\Domain\Spender\SpenderRepository;

class ExpenseListOverviewProjector extends MySQLProjector
{
    private $spenderRepository;
    private $categoryRepository;
    private $expenseRepository;

    public function __construct(
        \PDO $connection,
        EventSerializer $serializer,
        SpenderRepository $spenderRepository,
        CategoryRepository $categoryRepository,
        ExpenseRepository $expenseRepository
    ) {
        parent::__construct($connection, $serializer);
        $this->spenderRepository = $spenderRepository;
        $this->categoryRepository = $categoryRepository;
        $this->expenseRepository = $expenseRepository;
    }

    protected function whenExpenseListWasStarted(ExpenseListWasStarted $anEvent): void
    {
        $expenseListOverview = [
            'id' => $anEvent->expenseListId(),
            'name' => $anEvent->name(),
            'expensesBySpender' => [],
        ];

        $sql = <<<'SQL'
          INSERT INTO expense_list_overviews (expense_list_id, overview)
          VALUES (:expense_list_id, :overview);
SQL;
        $stmt = $this->connection()->prepare($sql);
        $stmt->execute([
            'expense_list_id' => $anEvent->expenseListId(),
            'overview' => json_encode($expenseListOverview),
        ]);
    }

    protected function whenExpenseWasAdded(ExpenseWasAdded $anEvent): void
    {
        $expenseListOverview = $this->expenseListOverviewOfId(ExpenseListId::ofId($anEvent->expenseListId()));

        if (!$this->spenderOfIdIsInitialized(SpenderId::ofId($anEvent->spenderId()), $expenseListOverview)) {
            $expenseListOverview = $this->initSpender($anEvent, $expenseListOverview);
        }

        $expenseListOverview = $this->saveAnExpense(
            ExpenseId::ofId($anEvent->expenseId()),
            CategoryId::ofId($anEvent->categoryId()),
            SpenderId::ofId($anEvent->spenderId()),
            $anEvent->amount(),
            $anEvent->description(),
            $expenseListOverview
        );

        $expenseListOverview = $this->updateTotalSpentOfSpender(
            SpenderId::ofId($anEvent->spenderId()),
            $expenseListOverview
        );

        $expenseListOverview = $this->updateBalances($expenseListOverview);
        $this->updateAnExpenseListOverviewOfId(ExpenseListId::ofId($anEvent->expenseListId()), $expenseListOverview);
    }

    protected function whenExpenseWasAltered(ExpenseWasAltered $anEvent): void
    {
        $anExpense = $this->expenseRepository->expenseOfId(ExpenseId::ofId($anEvent->expenseId()));
        $expenseListOverview = $this->expenseListOverviewOfId(ExpenseListId::ofId($anExpense->expenseListId()));

        $expenseListOverview = $this->saveAnExpense(
            ExpenseId::ofId($anEvent->expenseId()),
            CategoryId::ofId($anEvent->categoryId()),
            $anExpense->spenderId(),
            $anEvent->amount(),
            $anEvent->description(),
            $expenseListOverview
        );

        $expenseListOverview = $this->updateTotalSpentOfSpender($anExpense->spenderId(), $expenseListOverview);
        $expenseListOverview = $this->updateBalances($expenseListOverview);
        $this->updateAnExpenseListOverviewOfId($anExpense->expenseListId(), $expenseListOverview);
    }

    protected function whenExpenseWasRemoved(ExpenseWasRemoved $anEvent): void
    {
        $anExpense = $this->expenseRepository->expenseOfId(ExpenseId::ofId($anEvent->expenseId()));
        $expenseListOverview = $this->expenseListOverviewOfId(ExpenseListId::ofId($anExpense->expenseListId()));

        $expenseListOverview = $this->removeAnExpense(
            ExpenseId::ofId($anEvent->expenseId()),
            $anExpense->spenderId(),
            $expenseListOverview
        );

        $expenseListOverview = $this->updateTotalSpentOfSpender($anExpense->spenderId(), $expenseListOverview);
        $expenseListOverview = $this->updateBalances($expenseListOverview);
        $this->updateAnExpenseListOverviewOfId($anExpense->expenseListId(), $expenseListOverview);
    }

    private function expenseListOverviewOfId(ExpenseListId $expenseListId): array
    {
        $sql = <<<'SQL'
          SELECT overview FROM expense_list_overviews
          WHERE expense_list_id = :expense_list_id;
SQL;
        $stmt = $this->connection()->prepare($sql);
        $stmt->execute(['expense_list_id' => $expenseListId->toString()]);
        $stmt->bindColumn('overview', $expenseListOverview);
        $stmt->fetch(\PDO::FETCH_BOUND);

        return json_decode($expenseListOverview, true);
    }

    private function initSpender(ExpenseWasAdded $anEvent, array $expenseListOverview): array
    {
        $spender = $this->spenderRepository->spenderOfId(SpenderId::ofId($anEvent->spenderId()));

        $expenseListOverview['expensesBySpender'][$anEvent->spenderId()] = [
            'id' => $anEvent->spenderId(),
            'name' => $spender->name(),
            'totalSpent' => 0,
            'expenses' => [],
        ];

        return $expenseListOverview;
    }

    private function updateTotalSpentOfSpender(SpenderId $spenderId, array $expenseListOverview): array
    {
        $totalSpentOfSpender = array_reduce(
            $expenseListOverview['expensesBySpender'][$spenderId->toString()]['expenses'],
            function ($totalSpent, $expenses) {
                $totalSpent += $expenses['amount'];

                return $totalSpent;
            }
        );

        $expenseListOverview['expensesBySpender'][$spenderId->toString()]['totalSpent'] = $totalSpentOfSpender;

        return $expenseListOverview;
    }

    private function saveAnExpense(
        ExpenseId $expenseId,
        CategoryId $categoryId,
        SpenderId $spenderId,
        int $amount,
        string $description,
        array $expenseListOverview
    ): array {
        $category = $this->categoryRepository->categoryOfId($categoryId);

        $expenseListOverview['expensesBySpender'][$spenderId->toString()]['expenses'][$expenseId->toString()] = [
            'id' => $expenseId->toString(),
            'amount' => $amount,
            'description' => $description,
            'category' => [
                'id' => $category->id()->toString(),
                'name' => $category->name(),
            ],
        ];

        return $expenseListOverview;
    }

    private function removeAnExpense(ExpenseId $expenseId, SpenderId $spenderId, array $expenseListOverview): array
    {
        unset($expenseListOverview['expensesBySpender'][$spenderId->toString()]['expenses'][$expenseId->toString()]);

        return $expenseListOverview;
    }

    private function updateAnExpenseListOverviewOfId(ExpenseListId $expenseListId, array $expenseListOverview): void
    {
        $sql = <<<'SQL'
          UPDATE expense_list_overviews
          SET overview = :overview
          WHERE expense_list_id = :expense_list_id;
SQL;
        $stmt = $this->connection()->prepare($sql);
        $stmt->execute([
            'expense_list_id' => $expenseListId->toString(),
            'overview' => json_encode($expenseListOverview),
        ]);
    }

    private function spenderOfIdIsInitialized(SpenderId $spenderId, array $expenseListOverview): bool
    {
        return isset($expenseListOverview['expensesBySpender'][$spenderId->toString()]);
    }

    private function updateBalances($expenseListOverview): array
    {
        $averageSpent = $this->averageSpent($expenseListOverview);

        foreach ($expenseListOverview['expensesBySpender'] as $spenderId => $spenderData) {
            $expenseListOverview['expensesBySpender'][$spenderId]['balance'] =
                $spenderData['totalSpent'] - $averageSpent;
        }

        return $expenseListOverview;
    }

    private function averageSpent(array $expenseListOverview): float
    {
        return $this->totalSpentByAllSpenders($expenseListOverview) / $this->numberOfSpenders($expenseListOverview);
    }

    private function totalSpentByAllSpenders(array $expenseListOverview): int
    {
        return array_reduce(
            $expenseListOverview['expensesBySpender'],
            function ($totalSpent, $spenderData) {
                $totalSpent += $spenderData['totalSpent'];

                return $totalSpent;
            }
        );
    }

    private function numberOfSpenders(array $expenseListOverview): int
    {
        return count($expenseListOverview['expensesBySpender']);
    }
}
