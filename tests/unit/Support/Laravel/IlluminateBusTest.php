<?php 

use Mockery as m;

use Signal\Support\Laravel\IlluminateBus;

class IlluminateBusTest extends PHPUnit_Framework_TestCase{

    public function testImplementsInterface(){
        $this->assertInstanceOf(
            'Signal\Contracts\NamedEvent\Bus',
            $this->newBus()
        );
    }

    public function testFireForwardsToDispatcher()
    {
        $dispatcher = $this->mockDispatcher();
        $bus = $this->newBus($dispatcher);
        $eventName = 'admin.registered';
        $payload = ['test1','test2'];
        $halt = true;

        $dispatcher->shouldReceive('fire')
                   ->with($eventName, $payload, $halt)
                   ->once()
                   ->andReturn('test');

        $this->assertEquals('test',$bus->fire($eventName, $payload, $halt));
    }

    public function testListenForwardsToDispatcher()
    {
        $dispatcher = $this->mockDispatcher();
        $bus = $this->newBus($dispatcher);
        $eventName = 'admin.registered';
        $listener = 'substr';
        $priority = 58;

        $dispatcher->shouldReceive('listen')
                   ->with($eventName, $listener, $priority)
                   ->once()
                   ->andReturn('test');

        $this->assertNull($bus->listen($eventName, $listener, $priority));
    }

    public function newBus($dispatcher=null)
    {
        $dispatcher = $dispatcher ?: $this->mockDispatcher();
        return new IlluminateBus($dispatcher);
    }

    public function mockDispatcher()
    {
        return m::mock('Illuminate\Events\Dispatcher');
    }

    public function tearDown()
    {
        m::close();
    }

}