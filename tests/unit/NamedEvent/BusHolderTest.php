<?php 

use Mockery as m;

use Signal\NamedEvent\BusHolderTrait;
use Signal\NamedEvent\SilentBus;
use PHPUnit\Framework\TestCase;

class BusHolderTest extends TestCase
{

    protected $defaultBusClass = 'Signal\NamedEvent\Bus';

    public function testGetEventBusReturnsDefaultBus()
    {

        $holder = $this->newHolder();

        $this->assertInstanceOf(
            $this->defaultBusClass,
            $holder->getEventBus()
        );
    }

    public function testGetEventBusProducesOneBus()
    {

        $holder = $this->newHolder();

        $firstBus = $holder->getEventBus();

        $this->assertSame($firstBus, $holder->getEventBus());

    }

    public function testSetEventBusSetsBus()
    {

        $holder = $this->newHolder();
        $silentBob = new SilentBus;

        $this->assertInstanceOf('BusHolder', $holder->setEventBus($silentBob));

        $this->assertSame($silentBob, $holder->getEventBus());

    }

    public function testGetDispatcherReturnsDefaultBus()
    {

        $holder = $this->newHolder();

        $this->assertInstanceOf(
            $this->defaultBusClass,
            $holder->getDispatcher()
        );
    }

    public function testGetDistributorReturnsDefaultBus()
    {

        $holder = $this->newHolder();

        $this->assertInstanceOf(
            $this->defaultBusClass,
            $holder->getDistributor()
        );
    }

    public function testGetStaticEventBusReturnsSameInstanceOnTwoInstances()
    {
        $bus = new SilentBus();
        $holder = new StaticBusHolder();
        $holder2 = new StaticBusHolder();

        StaticBusHolder::setStaticEventBus($bus);

        $this->assertSame($bus, StaticBusHolder::getStaticEventBus());

        $this->assertSame($bus, $holder->getEventBus());
        $this->assertSame($bus, $holder2->getEventBus());

    }

    public function testGetStaticEventBusReturnsSameInstanceInExtendedClass()
    {
        $bus = new SilentBus();
        $holder = new StaticBusHolder();
        $holder2 = new ExtendedStaticHolder();

        StaticBusHolder::setStaticEventBus($bus);

        $this->assertSame($bus, $holder->getEventBus());
        $this->assertSame($bus, $holder2->getEventBus());

    }

    public function newHolder()
    {
        return new BusHolder;
    }

    public function tearDown()
    {
        m::close();
    }

}

class BusHolder
{
    use BusHolderTrait;
}

class StaticBusHolder
{
    use BusHolderTrait;
    protected static $staticEventBus;
}

class ExtendedStaticHolder extends StaticBusHolder{};
