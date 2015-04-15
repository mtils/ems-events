<?php 

use Mockery as m;

use Signal\NamedEvent\BusHolderTrait;
use Signal\NamedEvent\SilentBus;

class BusHolderTest extends PHPUnit_Framework_TestCase
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
            $holder->getDispatcher()
        );
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