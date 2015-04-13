<?php namespace Signal\NamedEvent;

use Signal\NamedEvent\Distributor;

trait ListenTrait{

    /**
     * @var \Signal\NamedEvent\Distributor
     **/
    protected $distributor;

    /**
     * Listen to event(s) $event
     *
     * @param mixed $events (string|array)
     * @param callable $listener
     * @param int $priority
     * @return void
     **/
    public function listen($events, $listener, $priority=0)
    {
        return $this->getEventBus()->listen($events, $listener, $priority);
    }

    /**
     * Return the event distributor
     *
     * @return \Signal\NamedEvent\Distributor
     **/
    public function getDistributor()
    {
        return $this->distributor;
    }

    /**
     * Set the event distributor
     *
     * @param \Signal\NamedEvent\Distributor $distributor
     * @return self
     **/
    public function setDistributor(Distributor $distributor)
    {
        $this->distributor = $distributor;
        return $this;
    }

}