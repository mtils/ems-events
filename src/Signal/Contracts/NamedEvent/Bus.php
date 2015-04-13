<?php namespace Signal\Contracts\NamedEvent;

/**
 * A bus allows to fire and listen to events. Depend on this interface
 * only if you really need to fire and listen.
 **/
interface Bus extends Dispatcher, Distributor{}