<?php

namespace Test\Application\Command\RegisterASpender;

use EventSourcing\EventPublisher\EmitterEventPublisher;
use MyExpenses\Domain\Spender\Spender;
use MyExpenses\Domain\Spender\SpenderWasRegistered;
use PHPUnit\Framework\TestCase;
use EventSourcing\Event\DummyEventSerializer;
use EventSourcing\EventStore\InMemoryEventStore;
use MyExpenses\Application\Command\RegisterASpender\RegisterASpenderCommand;
use MyExpenses\Application\Command\RegisterASpender\RegisterASpenderCommandHandler;
use MyExpenses\Infrastructure\Persistence\EventSourcedSpenderRepository;

class RegisterASpenderCommandHandlerTest extends TestCase
{
    const A_SPENDER_NAME = 'aSpenderName';

    /** @var DummyEventSerializer */
    private $serializer;
    /** @var EmitterEventPublisher */
    private $publisher;
    /** @var InMemoryEventStore */
    private $eventStore;
    /** @var EventSourcedSpenderRepository */
    private $spenderRepository;
    /** @var RegisterASpenderCommandHandler */
    private $sut;

    protected function setUp()
    {
        $this->serializer = new DummyEventSerializer();
        $this->publisher = new EmitterEventPublisher([]);
        $this->eventStore = new InMemoryEventStore($this->serializer);
        $this->spenderRepository = new EventSourcedSpenderRepository($this->eventStore, $this->publisher);
        $this->sut = new RegisterASpenderCommandHandler($this->spenderRepository);
    }

    /**
     * @test
     */
    public function itCreatesACategory()
    {
        $this->sut->handle(new RegisterASpenderCommand(self::A_SPENDER_NAME));

        $recordedEvent = $this->getSpenderWasRegisteredEvent();
        $this->assertInstanceOf(SpenderWasRegistered::class, $recordedEvent);
        $this->assertEquals(self::A_SPENDER_NAME, $recordedEvent->name());
    }

    private function getSpenderWasRegisteredEvent(): SpenderWasRegistered
    {
        return $this->eventStore->popEventOfType(SpenderWasRegistered::class, Spender::class);
    }
}
