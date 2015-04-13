<?php namespace Signal\NamedEvent;

use Signal\Contracts\NamedEvent\Bus as BusInterface;

class Bus implements BusInterface
{

    protected $listeners = [];

    protected $wildCardListeners = [];

    public function __construct()
    {

    }

    /**
     * {@inheritdoc}
     *
     * @param string $event The event name
     * @param array $payload The event parameters
     * @param bool $halt Stop propagation on return values !== null
     * @return mixed
     **/
    public function fire($event, $payload=[], $halt=false)
    {

        $returnValues = [];
        $payload = (array)$payload;

        foreach ($this->getListeners($event) as $priority=>$listeners)
        {
            foreach($listeners as $listener)
            {

                $returnValue = call_user_func_array($listener, $payload);

                if( $returnValue !== null && $halt)
                {
                    return $returnValue;
                }

                if ($returnValue === false) {
                    break 2;
                }

                $returnValues[] = $returnValue;
            }

        }

        return $halt ? null : $returnValues;
    }

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
        foreach ((array)$events as $event) {

            if ($this->isWildcardSyntax($event)){
                $this->addWildCardListener($event, $listener, $priority);
            }
            $this->addListener($event, $listener, $priority);

        }
    }

    public function isWildcardSyntax($event)
    {
        return strpos($event, '*') !== false;
    }

    public function wildCardMatchesEvent($wildcard, $event)
    {
        return fnmatch($wildcard, $event);
    }

    protected function getListeners($event)
    {
        $listeners = array_merge(
            $this->getExplicitListeners($event),
            $this->getWildcardListeners($event)
        );
        print_r(array_keys($listeners));
        krsort($listeners);
        return $listeners;

    }

    protected function getExplicitListeners($event)
    {
        if (!isset($this->listeners[$event])) {
            return [];
        }

        $listeners = $this->listeners[$event];

        return $listeners;

    }

    protected function getWildcardListeners($event)
    {
        $listeners = [];
        foreach ($this->wildCardListeners as $wildcard=>$priority) {
            if ($this->wildCardMatchesEvent($wildcard, $event)) {
                $listeners[$priority] = 
                    $this->wildCardListeners[$priority][$wildcard];
            }
        }
        return $listeners;
    }

    protected function addListener($event, $listener, $priority)
    {
        $this->listeners[$event][$priority][] = $listener;
    }

    protected function addWildCardListener($event, $listener, $priority)
    {
        $this->wildCardListeners[$event][$priority][] = $listener;
    }

}