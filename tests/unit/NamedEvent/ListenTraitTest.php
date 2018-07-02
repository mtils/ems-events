<?php 

use Mockery as m;

use Signal\NamedEvent\ListenTrait;
use PHPUnit\Framework\TestCase;

class ListenTraitTest extends TestCase
{

    public function testGetDistributorReturnBusAsDefault()
    {
        $trait = $this->newListenTrait();
        $this->assertInstanceOf(
            'Signal\NamedEvent\Bus',
            $trait->getDistributor()
        );
    }

    public function testListenForwardsToDispatcher()
    {
        $trait = $this->newListenTrait();
        $dispatcher = $this->mockDistributor();
        $dispatcher->shouldReceive('listen')
                   ->with('1','2','3')
                   ->once()
                   ->andReturn('test');
        $trait->setDistributor($dispatcher);

        $this->assertEquals('test', $trait->listen('1','2','3'));
    }


    public function mockDistributor(){
        return m::mock('Signal\Contracts\NamedEvent\Distributor');
    }

    public function newListenTrait()
    {
        return new ListenTraitObject();
    }

    public function tearDown()
    {
        m::close();
    }

}

class ListenTraitObject{
    use ListenTrait;
}
