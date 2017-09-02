<?php

namespace Test\Application\Command\AlterAnExpense;

use MyExpenses\Application\Command\RemoveAnExpense\RemoveAnExpenseCommand;
use MyExpenses\Application\Command\RemoveAnExpense\RemoveAnExpenseCommandHandler;
use MyExpenses\Domain\Expense\ExpenseId;
use MyExpenses\Domain\Expense\ExpenseWasRemoved;
use MyExpenses\Domain\Money\Money;
use PHPUnit\Framework\TestCase;
use EventSourcing\Event\DummyEventSerializer;
use EventSourcing\EventPublisher\EmitterEventPublisher;
use EventSourcing\EventStore\InMemoryEventStore;
use MyExpenses\Application\Command\AlterAnExpense\AlterAnExpenseCommandHandler;
use MyExpenses\Domain\Category\Category;
use MyExpenses\Domain\Expense\Expense;
use MyExpenses\Domain\ExpenseList\ExpenseList;
use MyExpenses\Domain\Spender\Spender;
use MyExpenses\Infrastructure\Persistence\EventSourcedCategoryRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedExpenseListRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedExpenseRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedSpenderRepository;

class RemoveAnExpenseCommandHandlerTest extends TestCase
{
    const AN_EXPENSE_LIST_NAME = 'aExpenseListName';
    const A_SPENDER_NAME = 'aSpenderName';
    const A_CATEGORY_NAME = 'aCategoryName';
    const AN_AMOUNT = 25;
    const A_DESCRIPTION = 'aDescription';

    /** @var AlterAnExpenseCommandHandler */
    private $sut;
    /** @var EventSourcedExpenseRepository */
    private $expenseRepository;
    /** @var EventSourcedExpenseListRepository */
    private $expenseListRepository;
    /** @var EventSourcedSpenderRepository */
    private $spenderRepository;
    /** @var EventSourcedCategoryRepository */
    private $categoryRepository;
    /** @var InMemoryEventStore */
    private $eventStore;
    /** @var DummyEventSerializer */
    private $serializer;
    /** @var EmitterEventPublisher */
    private $publisher;

    protected function setUp()
    {
        $this->serializer = new DummyEventSerializer();
        $this->eventStore = new InMemoryEventStore($this->serializer);
        $this->publisher = new EmitterEventPublisher([]);
        $this->expenseRepository = new EventSourcedExpenseRepository($this->eventStore, $this->publisher);
        $this->expenseListRepository = new EventSourcedExpenseListRepository($this->eventStore, $this->publisher);
        $this->spenderRepository = new EventSourcedSpenderRepository($this->eventStore, $this->publisher);
        $this->categoryRepository = new EventSourcedCategoryRepository($this->eventStore, $this->publisher);

        $this->sut = new RemoveAnExpenseCommandHandler(
            $this->expenseListRepository,
            $this->expenseRepository
        );
    }

    /**
     * @test
     */
    public function itRemovesAnExpense()
    {
        $anExpenseList = ExpenseList::named(self::AN_EXPENSE_LIST_NAME);
        $aSpender = Spender::named(self::A_SPENDER_NAME);
        $aCategory = Category::createWithData(self::A_CATEGORY_NAME, $anExpenseList);

        $anExpense = $anExpenseList->addAnExpense(
            Money::fromAmount(self::AN_AMOUNT),
            $aCategory,
            $aSpender,
            self::A_DESCRIPTION
        );

        $this->expenseListRepository->addAnExpenseList($anExpenseList);
        $this->spenderRepository->addASpender($aSpender);
        $this->categoryRepository->addACategory($aCategory);
        $this->expenseRepository->addAnExpense($anExpense);

        $this->sut->handle(
            new RemoveAnExpenseCommand(
                $anExpenseList->id()->toString(),
                $anExpense->id()->toString()
            )
        );

        $recordedEvent = $this->getExpenseWasRemovedEvent();

        $this->assertInstanceOf(ExpenseWasRemoved::class, $recordedEvent);
        $this->assertTrue($anExpense->id()->equals(ExpenseId::ofId($recordedEvent->expenseId())));
    }

    private function getExpenseWasRemovedEvent(): ExpenseWasRemoved
    {
        return $this->eventStore->popEventOfType(ExpenseWasRemoved::class, Expense::class);
    }
}
