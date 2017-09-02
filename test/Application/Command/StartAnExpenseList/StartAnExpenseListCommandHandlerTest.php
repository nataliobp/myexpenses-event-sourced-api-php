<?php

namespace Test\Application\Command\StartAnExpenseList;

use EventSourcing\Event\DummyEventSerializer;
use EventSourcing\EventPublisher\EmitterEventPublisher;
use EventSourcing\EventStore\InMemoryEventStore;
use MyExpenses\Application\Command\StartAnExpenseList\StartAnExpenseListCommand;
use MyExpenses\Application\Command\StartAnExpenseList\StartAnExpenseListCommandHandler;
use MyExpenses\Domain\ExpenseList\ExpenseList;
use MyExpenses\Domain\ExpenseList\ExpenseListWasStarted;
use MyExpenses\Infrastructure\Persistence\EventSourcedExpenseListRepository;
use PHPUnit\Framework\TestCase;

class StartAnExpenseListCommandHandlerTest extends TestCase
{
    const AN_EXPENSE_LIST_NAME = 'anExpenseListName';

    /** @var DummyEventSerializer */
    private $serializer;
    /** @var EmitterEventPublisher */
    private $publisher;
    /** @var InMemoryEventStore */
    private $eventStore;
    /** @var EventSourcedExpenseListRepository */
    private $expenseListRepository;
    /** @var StartAnExpenseListCommandHandler */
    private $sut;

    protected function setUp()
    {
        $this->serializer = new DummyEventSerializer();
        $this->publisher = new EmitterEventPublisher([]);
        $this->eventStore = new InMemoryEventStore($this->serializer);
        $this->expenseListRepository = new EventSourcedExpenseListRepository($this->eventStore, $this->publisher);
        $this->sut = new StartAnExpenseListCommandHandler($this->expenseListRepository);
    }

    /**
     * @test
     */
    public function itCreatesACategory()
    {
        $this->sut->handle(new StartAnExpenseListCommand(self::AN_EXPENSE_LIST_NAME));

        $recordedEvent = $this->getExpenseListWasStartedEvent();
        $this->assertInstanceOf(ExpenseListWasStarted::class, $recordedEvent);
        $this->assertEquals(self::AN_EXPENSE_LIST_NAME, $recordedEvent->name());
    }

    private function getExpenseListWasStartedEvent(): ExpenseListWasStarted
    {
        return $this->eventStore->popEventOfType(ExpenseListWasStarted::class, ExpenseList::class);
    }
}
