<?php

namespace Test\Application\Command\AddAnExpense;

use PHPUnit\Framework\TestCase;
use EventSourcing\Event\DummyEventSerializer;
use EventSourcing\EventPublisher\EmitterEventPublisher;
use EventSourcing\EventStore\InMemoryEventStore;
use MyExpenses\Application\Command\AddAnExpense\AddAnExpenseCommand;
use MyExpenses\Application\Command\AddAnExpense\AddAnExpenseCommandHandler;
use MyExpenses\Domain\Category\Category;
use MyExpenses\Domain\Expense\Expense;
use MyExpenses\Domain\Expense\ExpenseWasAdded;
use MyExpenses\Domain\ExpenseList\ExpenseList;
use MyExpenses\Domain\Spender\Spender;
use MyExpenses\Infrastructure\Persistence\EventSourcedCategoryRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedExpenseListRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedExpenseRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedSpenderRepository;

class AddAnExpenseCommandHandlerTest extends TestCase
{
    const AN_EXPENSE_LIST_NAME = 'aExpenseListName';
    const AN_AMOUNT = 25;
    const A_DESCRIPTION = 'aDescription';
    const A_SPENDER_NAME = 'aSpenderName';
    const A_CATEGORY_NAME = 'aCategoryName';

    /** @var AddAnExpenseCommandHandler */
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

        $this->sut = new AddAnExpenseCommandHandler(
            $this->expenseRepository,
            $this->expenseListRepository,
            $this->spenderRepository,
            $this->categoryRepository
        );
    }

    /**
     * @test
     */
    public function itAddsAnExpense()
    {
        $anExpenseList = ExpenseList::named(self::AN_EXPENSE_LIST_NAME);
        $aSpender = Spender::named(self::A_SPENDER_NAME);
        $aCategory = Category::createWithData(self::A_CATEGORY_NAME, $anExpenseList);

        $this->expenseListRepository->addAnExpenseList($anExpenseList);
        $this->spenderRepository->addASpender($aSpender);
        $this->categoryRepository->addACategory($aCategory);

        $this->sut->handle(
            new AddAnExpenseCommand(
                self::AN_AMOUNT,
                self::A_DESCRIPTION,
                $aCategory->id()->toString(),
                $aSpender->id()->toString(),
                $anExpenseList->id()->toString()
            )
        );

        $recordedEvent = $this->getExpenseWasAddedEvent();

        $this->assertInstanceOf(ExpenseWasAdded::class, $recordedEvent);
        $this->assertEquals($recordedEvent->amount(), self::AN_AMOUNT);
        $this->assertEquals($recordedEvent->categoryId(), $aCategory->id()->toString());
        $this->assertEquals($recordedEvent->spenderId(), $aSpender->id()->toString());
        $this->assertEquals($recordedEvent->expenseListId(), $anExpenseList->id()->toString());
    }

    private function getExpenseWasAddedEvent(): ExpenseWasAdded
    {
        return $this->eventStore->popEventOfType(ExpenseWasAdded::class, Expense::class);
    }
}
