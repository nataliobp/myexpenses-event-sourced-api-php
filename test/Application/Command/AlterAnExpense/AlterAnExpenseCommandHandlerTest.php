<?php

namespace Test\Application\Command\AlterAnExpense;

use MyExpenses\Domain\Money\Money;
use PHPUnit\Framework\TestCase;
use EventSourcing\Event\DummyEventSerializer;
use EventSourcing\EventPublisher\EmitterEventPublisher;
use EventSourcing\EventStore\InMemoryEventStore;
use MyExpenses\Application\Command\AlterAnExpense\AlterAnExpenseCommand;
use MyExpenses\Application\Command\AlterAnExpense\AlterAnExpenseCommandHandler;
use MyExpenses\Domain\Category\Category;
use MyExpenses\Domain\Expense\Expense;
use MyExpenses\Domain\Expense\ExpenseWasAltered;
use MyExpenses\Domain\ExpenseList\ExpenseList;
use MyExpenses\Domain\Spender\Spender;
use MyExpenses\Infrastructure\Persistence\EventSourcedCategoryRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedExpenseListRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedExpenseRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedSpenderRepository;

class AlterAnExpenseCommandHandlerTest extends TestCase
{
    const AN_EXPENSE_LIST_NAME = 'aExpenseListName';
    const A_SPENDER_NAME = 'aSpenderName';
    const A_CATEGORY_NAME = 'aCategoryName';
    const AN_ORIGINAL_AMOUNT = 25;
    const AN_ORIGINAL_DESCRIPTION = 'originalDescription';
    const A_NEW_AMOUNT = 30;
    const A_NEW_DESCRIPTION = 'aNewDescription';
    const ANOTHER_CATEGORY_NAME = 'anotherCategoryName';

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

        $this->sut = new AlterAnExpenseCommandHandler(
            $this->expenseRepository,
            $this->categoryRepository
        );
    }

    /**
     * @test
     */
    public function itAltersAnExpense()
    {
        $anExpenseList = ExpenseList::named(self::AN_EXPENSE_LIST_NAME);
        $aSpender = Spender::named(self::A_SPENDER_NAME);
        $aCategory = Category::createWithData(self::A_CATEGORY_NAME, $anExpenseList);
        $anotherCategory = Category::createWithData(self::ANOTHER_CATEGORY_NAME, $anExpenseList);

        $anExpense = $anExpenseList->addAnExpense(
            Money::fromAmount(self::AN_ORIGINAL_AMOUNT),
            $aCategory,
            $aSpender,
            self::AN_ORIGINAL_DESCRIPTION
        );

        $this->expenseListRepository->addAnExpenseList($anExpenseList);
        $this->spenderRepository->addASpender($aSpender);
        $this->categoryRepository->addACategory($aCategory);
        $this->categoryRepository->addACategory($anotherCategory);
        $this->expenseRepository->addAnExpense($anExpense);

        $this->sut->handle(
            new AlterAnExpenseCommand(
                $anExpense->id()->toString(),
                self::A_NEW_AMOUNT,
                self::A_NEW_DESCRIPTION,
                $anotherCategory->id()->toString()
            )
        );

        $recordedEvent = $this->getExpenseWasAlteredEvent();

        $this->assertInstanceOf(ExpenseWasAltered::class, $recordedEvent);
        $this->assertEquals($recordedEvent->amount(), self::A_NEW_AMOUNT);
        $this->assertEquals($recordedEvent->categoryId(), $anotherCategory->id()->toString());
        $this->assertEquals($recordedEvent->description(), self::A_NEW_DESCRIPTION);
    }

    private function getExpenseWasAlteredEvent(): ExpenseWasAltered
    {
        return $this->eventStore->popEventOfType(ExpenseWasAltered::class, Expense::class);
    }
}
