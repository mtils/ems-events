<?php namespace Signal\Contracts\IOC;

/**
 * A callable creator creates a callable (Closure) which can later be called.
 * This is needed for almost all listener implementations where you like to
 * listen by other things than closures. A callable creator has to be fast.
 * The event dispatcher will call this method every time a event is fired which
 * the listener subscribed to. Its the responsibility of the CallableCreator to
 * deceide if the callable will be created every time or just once.
 **/
interface CallableCreator
{

    /**
     * Creates a callable which can be used to call later
     *
     * @mixed arg
     * @return callable
     **/
    public function makeCallable($arg);

}