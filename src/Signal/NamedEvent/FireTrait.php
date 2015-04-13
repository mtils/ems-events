<?php namespace Signal\NamedEvent;


use Signal\Contracts\NamedEvent\Dispatcher;

trait FireTrait
{

    /**
     * @var \Signal\Contracts\NamedEvent\Dispatcher
     **/
    protected $dispatcher;

    protected $firedEvents = [];

    /**
     * Fire an event with payload $payload. If $halt is set to true
     * stop propagation if some subscriber return something trueish
     *
     * @param string $event The event name
     * @param array $payload The event parameters
     * @param bool $halt Stop propagation on trueish return values
     * @return mixed
     **/
    public function fire($event, $payload=[], $halt=false)
    {
        $this->firedEvents[$event] = true;
        return $this->getDispatcher()->fire($event, $payload, $halt);
    }

    /**
     * Fire an event if a name was passed
     *
     * @param string $event The event name
     * @param array $payload The event parameters
     * @param bool $halt Stop propagation on trueish return values
     * @return mixed
     * @see self::fire()
     **/
    public function fireIfNamed($event, $payload=[], $halt=false)
    {
        if(!$event){
            return;
        }
        return $this->fire($event, $payload, $halt);
    }

    /**
     * Fire an event once. The next event with this name will be ignored
     *
     * @param string $event The event name
     * @param array $payload The event parameters
     * @param bool $halt Stop propagation on trueish return values
     * @return mixed
     * @see self::fire()
     **/
    public function fireOnce($event, $payload=[], $halt=false)
    {
        if(isset($this->firedEvents[$event])){
            return;
        }
        return $this->fire($event, $payload, $halt);
    }

    /**
     * Fire an event once if named
     *
     * @param string $event The event name
     * @param array $payload The event parameters
     * @param bool $halt Stop propagation on trueish return values
     * @return mixed
     * @see self::fire()
     **/
    public function fireOnceIfNamed($event, $payload=[], $halt=false)
    {
        if(!$event){
            return;
        }
        return $this->fireOnce($event, $payload, $halt);
    }

    /**
     * Return the dispatcher
     *
     * @return \Signal\Contracts\NamedEvent\Dispatcher
     **/
    public function getDispatcher()
    {
        if(!$this->dispatcher){
            $this->dispatcher = new Bus();
        }
        return $this->dispatcher;
    }

    /**
     * Set the dispatcher
     *
     * @param \Signal\Contracts\NamedEvent\Dispatcher $dispatcher
     * @return self
     **/
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        return $this;
    }

}