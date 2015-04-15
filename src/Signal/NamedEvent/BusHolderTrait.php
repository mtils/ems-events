<?php namespace Signal\NamedEvent;

use Signal\Contracts\NamedEvent\Bus as BusInterface;

trait BusHolderTrait{

    use FireTrait;
    use ListenTrait;

    /**
     * @var \Signal\Contracts\NamedEvent\Bus
     **/
    protected $eventBus;

    /**
     * Return the eventBus
     *
     * @return \Signal\Contracts\NamedEvent\Bus
     **/
    public function getEventBus()
    {
        if (!$this->eventBus) {
            $this->eventBus = new Bus;
        }
        return $this->eventBus;
    }

    /**
     * Set the event bus
     *
     * @param \Signal\Contracts\NamedEvent\Bus $eventBus
     * @return self
     **/
    public function setEventBus(BusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
        return $this;
    }

    /**
     * Return the dispatcher
     *
     * @return \Signal\Contracts\NamedEvent\Dispatcher
     **/
    public function getDispatcher()
    {
        return $this->getEventBus();
    }

    /**
     * Return the event distributor
     *
     * @return \Signal\NamedEvent\Distributor
     **/
    public function getDistributor()
    {
        return $this->getEventBus();
    }

}