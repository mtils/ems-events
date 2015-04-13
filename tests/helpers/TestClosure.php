<?php

class TestClosure implements Countable, ArrayAccess
{

    public $receivedArgs = [];

    public $callCount = 0;

    public $shouldReturn;

    public function __invoke(){

        $this->receivedArgs = func_get_args();
        $this->callCount++;

        return $this->shouldReturn;

    }

    public function count()
    {
        return count($this->receivedArgs);
    }

    public function arg($index)
    {
        return $this->receivedArgs[$index];
    }

    public function offsetExists($offset)
    {
        return isset($this->receivedArgs[$offset]);
    }

    public function offsetGet($offset)
    {
        if($this->offsetExists($offset)){
            return $this->receivedArgs[$offset];
        }
    }

    public function offsetSet($offset, $value)
    {
        // Not needed
    }

    public function offsetUnset($offset)
    {
        // not needed
    }


}