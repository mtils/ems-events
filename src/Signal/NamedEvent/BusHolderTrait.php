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
            if (isset(static::$staticEventBus)) {
                return static::$staticEventBus;
            }
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
     * Get the static event bus
     * @see self::setStaticEventBus()
     *
     * @return \Signal\Contracts\NamedEvent\Bus
     **/
    public static function getStaticEventBus()
    {
        return static::$staticEventBus;
    }

    /**
     * Set a static event bus. You have to declare a static variable
     * static $staticEventBus property to make this work. PHP traits do not
     * allow static properties in traits.
     * If you set one here all classes and subclasses will share the same event
     * bus.
     *
     * @param \Signal\Contracts\NamedEvent\Bus
     * @return void
     **/
    public static function setStaticEventBus(BusInterface $eventBus)
    {
        static::$staticEventBus = $eventBus;
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