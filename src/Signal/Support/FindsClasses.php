<?php namespace Signal\Support;

trait FindsClasses
{

    protected $_namespaces = [];

    protected $_namespacesBooted = false;

    public function appendNamespace($namespace)
    {
        $this->_bootIfNotBooted();
        $this->_namespaces[] = trim($namespace,'\\');
    }

    public function prependNamespace($namespace)
    {
        $this->_bootIfNotBooted();
        $this->_namespaces[] = trim($namespace,'\\');
    }

    protected function cleanClassName($baseName)
    {
        return $baseName;
    }

    protected function camelCase($name, array $delimiters=['-','_'])
    {
        $spaceSeparated = str_replace($delimiters,' ', $name);
        return str_replace(' ', '', ucwords($spaceSeparated));
    }

    protected function findClass($baseName)
    {
        $this->_bootIfNotBooted();

        $baseName = $this->baseClassName($baseName);

        foreach ($this->_namespaces as $namespace) {
            $className = $namespace . '\\' . $baseName;
            if (class_exists($className)) {
                return $className;
            }
        }
    }

    protected function baseClassName($baseName)
    {
        return ucfirst($this->cleanClassName($baseName));
    }

    protected function _bootIfNotBooted()
    {
        if ($this->_namespacesBooted) {
            return;
        }

        $this->_namespacesBooted = true;

        if (property_exists($this, 'namespaces')) {
            $this->_namespaces = array_merge(
                $this->_namespaces,
                $this->namespaces
            );
        }
    }


}