<?php namespace Signal\Support\Laravel;


use Illuminate\Events\Dispatcher;

use Signal\Contracts\NamedEvent\Bus;

class IlluminateBus implements Bus{

    protected $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
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
        return $this->dispatcher->fire($event, $payload, $halt);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $events (string|array)
     * @param callable $listener
     * @param int $priority
     * @return void
     **/
    public function listen($events, $listener, $priority=0)
    {
        $this->dispatcher->listen($events, $listener, $priority);
    }

}