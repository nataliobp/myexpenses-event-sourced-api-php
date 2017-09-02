<?php

namespace Test\Application\Command\AddACategory;

use EventSourcing\Event\DummyEventSerializer;
use EventSourcing\EventPublisher\EmitterEventPublisher;
use EventSourcing\EventStore\InMemoryEventStore;
use MyExpenses\Application\Command\CreateACategory\CreateACategoryCommand;
use MyExpenses\Application\Command\CreateACategory\CreateACategoryCommandHandler;
use MyExpenses\Domain\Category\Category;
use MyExpenses\Domain\Category\CategoryWasCreated;
use MyExpenses\Domain\ExpenseList\ExpenseList;
use MyExpenses\Infrastructure\Persistence\EventSourcedCategoryRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedExpenseListRepository;
use PHPUnit\Framework\TestCase;

class AddACategoryCommandHandlerTest extends TestCase
{
    const A_CATEGORY_NAME = 'aCategoryName';
    const AN_EXPENSE_LIST_NAME = 'anExpenseListName';

    /** @var DummyEventSerializer */
    private $serializer;
    /** @var EmitterEventPublisher */
    private $publisher;
    /** @var InMemoryEventStore */
    private $eventStore;
    /** @var EventSourcedExpenseListRepository */
    private $expenseListRepository;
    /** @var EventSourcedCategoryRepository */
    private $categoryRepository;
    /** @var CreateACategoryCommandHandler */
    private $sut;

    protected function setUp()
    {
        $this->serializer = new DummyEventSerializer();
        $this->publisher = new EmitterEventPublisher([]);
        $this->eventStore = new InMemoryEventStore($this->serializer);
        $this->expenseListRepository = new EventSourcedExpenseListRepository($this->eventStore, $this->publisher);
        $this->categoryRepository = new EventSourcedCategoryRepository($this->eventStore, $this->publisher);
        $this->sut = new CreateACategoryCommandHandler($this->expenseListRepository, $this->categoryRepository);
    }

    /**
     * @test
     */
    public function itCreatesACategory()
    {
        $anExpenseList = ExpenseList::named(self::AN_EXPENSE_LIST_NAME);
        $this->expenseListRepository->addAnExpenseList($anExpenseList);
        $this->sut->handle(new CreateACategoryCommand(self::A_CATEGORY_NAME, $anExpenseList->id()->toString()));

        $recordedEvent = $this->getCategoryWasCreatedEvent();
        $this->assertInstanceOf(CategoryWasCreated::class, $recordedEvent);
        $this->assertEquals(self::A_CATEGORY_NAME, $recordedEvent->name());
        $this->assertEquals($anExpenseList->id()->toString(), $recordedEvent->expenseListId());
    }

    private function getCategoryWasCreatedEvent(): CategoryWasCreated
    {
        return $this->eventStore->popEventOfType(CategoryWasCreated::class, Category::class);
    }
}
