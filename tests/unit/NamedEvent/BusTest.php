<?php 

use Mockery as m;

use Signal\NamedEvent\Bus;
use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../helpers/TestClosure.php';


class BusTest extends TestCase{

    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            'Signal\Contracts\NamedEvent\Bus',
            $this->newBus()
        );
    }

    public function testFireUsesCallsCallable()
    {
        $closure = $this->newClosure();
        $bus = $this->newBus();
        $eventName = 'test.event';

        $bus->listen($eventName, $closure);
        $bus->fire($eventName);

        $this->assertEquals(1, $closure->callCount);

        $bus->fire($eventName);
        $bus->fire($eventName);
        $bus->fire($eventName);

        $this->assertEquals(4, $closure->callCount);
    }

    public function testFirePassesParams()
    {
        $closure = $this->newClosure();
        $bus = $this->newBus();
        $eventName = 'test.event';
        $params1 = ['oh', 1, false];
        $params2 = [15];

        $bus->listen($eventName, $closure);
        $bus->fire($eventName, $params1);

        $this->assertEquals($params1, $closure->receivedArgs);

        $bus->fire($eventName, $params2);
        $this->assertEquals($params2, $closure->receivedArgs);

    }

    public function testFireScalarFiresArray()
    {
        $closure = $this->newClosure();
        $bus = $this->newBus();
        $eventName = 'test.event';
        $params1 = 'test';

        $bus->listen($eventName, $closure);
        $bus->fire($eventName, $params1);

        $this->assertEquals([$params1], $closure->receivedArgs);
    }

    public function testFireReturnsArrayIfReturnValuesPresent()
    {
        $closure = $this->newClosure();
        $closure->shouldReturn = 'test';
        $closure2 = $this->newClosure();
        $closure2->shouldReturn = 'test2';
        $bus = $this->newBus();

        $eventName = 'test.event';
        $bus->listen($eventName, $closure);
        $bus->listen($eventName, $closure2);

        $this->assertEquals(
            [$closure->shouldReturn, $closure2->shouldReturn],
            $bus->fire($eventName)
        );
    }

    public function testFireReturnsFirstHitWhenHaltIsSet()
    {
        $closure = $this->newClosure();
        $closure->shouldReturn = 'test';
        $closure2 = $this->newClosure();
        $closure2->shouldReturn = 'test2';
        $bus = $this->newBus();

        $eventName = 'test.event';
        $bus->listen($eventName, $closure);
        $bus->listen($eventName, $closure2);

        $this->assertEquals(
            $closure->shouldReturn,
            $bus->fire($eventName, [], true)
        );

        $this->assertEquals(0, $closure2->callCount);
    }

    public function testFireStopsPropagationIfListenerReturnsFalse()
    {
        $closure = $this->newClosure();
        $closure->shouldReturn = false;
        $closure2 = $this->newClosure();
        $closure2->shouldReturn = 'test2';
        $bus = $this->newBus();

        $eventName = 'test.event';
        $bus->listen($eventName, $closure);
        $bus->listen($eventName, $closure2);

        $bus->fire($eventName, []);

        $this->assertEquals(0, $closure2->callCount);
    }

    public function testFireReturnsNullIfHaltIsSetAndNoOneListens()
    {
        $closure = $this->newClosure();
        $closure->shouldReturn = false;
        $closure2 = $this->newClosure();
        $closure2->shouldReturn = 'test2';
        $bus = $this->newBus();

        $eventName = 'test.event';
        $bus->listen($eventName, $closure);
        $bus->listen($eventName, $closure2);

        $this->assertNull($bus->fire('other.event', [], true));

        $this->assertEquals(0, $closure->callCount);
        $this->assertEquals(0, $closure2->callCount);
    }

    public function testFireReturnsArrayIfHaltIsNotSetAndNoOneListens()
    {
        $closure = $this->newClosure();
        $closure->shouldReturn = false;
        $closure2 = $this->newClosure();
        $closure2->shouldReturn = 'test2';
        $bus = $this->newBus();

        $eventName = 'test.event';
        $bus->listen($eventName, $closure);
        $bus->listen($eventName, $closure2);

        $this->assertEquals([], $bus->fire('other.event', []));

        $this->assertEquals(0, $closure->callCount);
        $this->assertEquals(0, $closure2->callCount);
    }

    public function testFireRespectsPriorityOnExplicitListeners()
    {
        $closure = $this->newClosure();
        $closure->shouldReturn = 'test1';
        $closure2 = $this->newClosure();
        $closure2->shouldReturn = 'test2';
        $closure3 = $this->newClosure();
        $closure3->shouldReturn = 'test3';

        $bus = $this->newBus();

        $eventName = 'test.event';
        $bus->listen($eventName, $closure, 0);
        $bus->listen($eventName, $closure2, 15);
        $bus->listen($eventName, $closure3, 9);

        $this->assertEquals(
            [
                $closure2->shouldReturn,
                $closure3->shouldReturn,
                $closure->shouldReturn
            ],
            $bus->fire($eventName, [])
        );

    }

    public function testWildcardListenTriggersClosure()
    {
        $closure = $this->newClosure();
        $closure->shouldReturn = 'test1';
        $bus = $this->newBus();

        $eventName = 'auth.login';

        $bus->listen('auth.*', $closure, 0);
        $bus->listen('auth.*', $closure, 0);

        $bus->fire($eventName, ['test']);

        $this->assertEquals('test', $closure[0]);
        $this->assertEquals(2, $closure->callCount);

    }

    public function testWildcardListenDoesNotTriggerClosureIfNotMatched()
    {
        $closure = $this->newClosure();
        $closure->shouldReturn = 'test1';
        $bus = $this->newBus();

        $eventName = 'auth.login';

        $bus->listen('authi.*', $closure, 0);

        $bus->fire($eventName, ['test']);

        $this->assertEquals(0, $closure->callCount);

    }

    public function testMixedWildcardAndExplicitPriority()
    {
        $closure = $this->newClosure();
        $closure->shouldReturn = 'test1';
        $closure2 = $this->newClosure();
        $closure2->shouldReturn = 'test2';
        $closure3 = $this->newClosure();
        $closure3->shouldReturn = 'test3';
        $bus = $this->newBus();

        $eventName = 'auth.login';

        $bus->listen('auth.login', $closure,   0);
        $bus->listen('auth.*',     $closure2,  4);
        $bus->listen('auth.login', $closure3, 18);

        $bus->fire($eventName, ['test']);

        $this->assertEquals(
            [
                $closure3->shouldReturn,
                $closure2->shouldReturn,
                $closure->shouldReturn
            ],
            $bus->fire($eventName, ['test'])
        );

    }

    /**
     * @expectedException \InvalidArgumentException
     **/
    public function testListenWithoutCallableThrowsException()
    {
        $bus = $this->newBus();
        $bus->listen('some-event', 'string');
    }

    public function newBus()
    {
        return new Bus();
    }

    public function newClosure()
    {
        return new TestClosure;
    }

}
