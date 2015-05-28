<?php namespace Signal\Support;
 
trait Extendable
{

    protected $_extensions = [];

    public function extend($name, callable $callable)
    {
        $this->_extensions[$name] = $callable;
        return $this;
    }

    protected function hasExtend($name)
    {
        return isset($this->_extensions[$name]);
    }

    protected function callExtend($name, array $params=[])
    {

        if (!$this->hasExtend($name)) {
            return;
        }

        return call_user_func_array($this->_extensions[$name], $params);
    }

    protected function getExtend($name)
    {
        if ($this->hasExtend($name)) {
            return $this->_extensions[$name];
        }
    }

} 