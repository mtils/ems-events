<?php 

use Mockery as m;

use Signal\NamedEvent\FireTrait;
use PHPUnit\Framework\TestCase;

class FireTraitTest extends TestCase
{

    public function testGetDispatcherReturnBusAsDefault()
    {
        $trait = $this->newFireTrait();
        $this->assertInstanceOf(
            'Signal\NamedEvent\Bus',
            $trait->getDispatcher()
        );
    }

    public function testFireForwardsToDispatcher()
    {
        $trait = $this->newFireTrait();
        $dispatcher = $this->mockDispatcher();
        $dispatcher->shouldReceive('fire')
                   ->with('1','2','3')
                   ->once()
                   ->andReturn('test');
        $trait->setDispatcher($dispatcher);

        $this->assertEquals('test', $trait->fire('1','2','3'));
    }

    public function testFireIfNamedDoesNotFireIfNotNamed()
    {
        $trait = $this->newFireTrait();
        $dispatcher = $this->mockDispatcher();
        $dispatcher->shouldReceive('fire')
                   ->never();
        $trait->setDispatcher($dispatcher);

        $this->assertNull($trait->fireIfNamed('','2','3'));
    }

    public function testFireIfNamedFiresIfNamed()
    {
        $trait = $this->newFireTrait();
        $dispatcher = $this->mockDispatcher();
        $dispatcher->shouldReceive('fire')
                   ->with('1','2','3')
                   ->once()
                   ->andReturn('test');
        $trait->setDispatcher($dispatcher);

        $this->assertEquals('test', $trait->fireIfNamed('1','2','3'));
    }

    public function testFireOnceFiresOnce()
    {
        $trait = $this->newFireTrait();
        $dispatcher = $this->mockDispatcher();
        $dispatcher->shouldReceive('fire')
                   ->with('1','2','3')
                   ->once()
                   ->andReturn('test');
        $trait->setDispatcher($dispatcher);

        $this->assertEquals('test', $trait->fireOnce('1','2','3'));

        $this->assertNull($trait->fireOnce('1','2','3'));

    }

    public function testFireOnceIfNamedFiresNeverIfEventNotNamed()
    {
        $trait = $this->newFireTrait();
        $dispatcher = $this->mockDispatcher();
        $dispatcher->shouldReceive('fire')
                   ->never();
        $trait->setDispatcher($dispatcher);

        $this->assertNull($trait->fireOnceIfNamed('','2','3'));

        $dispatcher->shouldReceive('fire')
                   ->with('1','2','3')
                   ->once()
                   ->andReturn('test');

        $this->assertEquals('test', $trait->fireOnceIfNamed('1','2','3'));

        $this->assertNull($trait->fireOnceIfNamed('1','2','3'));

    }

    public function mockDispatcher(){
        return m::mock('Signal\Contracts\NamedEvent\Dispatcher');
    }

    public function newFireTrait()
    {
        return new FireTraitObject();
    }

    public function tearDown()
    {
        m::close();
    }

}

class FireTraitObject{
    use FireTrait;
}
