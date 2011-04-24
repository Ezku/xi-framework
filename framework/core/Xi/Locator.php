<?php
/**
 * @category    Xi
 * @package     Xi_Locator
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Locator extends Zend_Registry
{
    /**
     * @var string which class to instantiate branches as
     */
    protected $_branchClass = __CLASS__;

    /**
     * @var Xi_Locator_Injector
     */
    protected $_injector;
    
    /**
     * @var Xi_Locator
     */
    protected $_parent;

    /**
     * Constructor. Provide an array of resources to set initial contents.
     * Additionally provide a Locator instance to set as parent.
     *
     * @param null|array
     * @param null|Xi_Locator parent
     * @return void
     */
    public function __construct($defaults = null, self $parent = null)
    {
        parent::__construct(array(), self::ARRAY_AS_PROPS);
        $this->setParent($parent);
        if (null !== $defaults) {
            $this->setResources($defaults);
        }
    }

    /**
     * Set parent locator. Used when unserializing.
     *
     * @link __wakeUp()
     * @param Xi_Locator parent
     * @return Xi_Locator
     */
    public function setParent(self $parent = null)
    {
        $this->_parent = $parent;
        $this->_injector = new Xi_Locator_Injector($parent instanceof self ? $parent : $this);
        return $this;
    }
    
    /**
     * @param mixed $target
     * @return void
     */
    public function inject($target)
    {
        return $this->_injector->inject($target);
    }

    /**
     * ArrayObject forgets its internal state when serialized: re-establish that
     * state.
     *
     * - Enable ARRAY_AS_PROPS
     * - Reassociate branches with their parents
     *
     * @return void
     */
    public function __wakeup()
    {
        $this->setFlags(self::ARRAY_AS_PROPS);
        foreach (new ArrayIterator($this) as $branch) {
            if ($branch instanceof self) {
                $branch->setParent($this);
            }
        }
    }

    /**
     * Create new branch
     *
     * @param null|array
     * @return Xi_Locator
     */
    public function createBranch($defaults = null)
    {
        $instance = Xi_Class::create($this->_branchClass, array($defaults, $this));
        return $instance;
    }

    /**
     * @param string method name
     * @param array method arguments
     */
    public function __call($method, $args)
    {
        return $this->getResource($method, $args);
    }

    /**
     * Recursively transform Factories in a collection into their values
     *
     * @param array
     * @return string
     * @return array
     */
    public function getFactoryValues($target, $args = null)
    {
        if ($target instanceof Xi_Factory_Interface) {
            return $target->get($args);
        } elseif ($target instanceof self) {
            return $target->getResources();
        } elseif (is_array($target)) {
            foreach ($target as $k => $v) {
                $target[$k] = $this->getFactoryValues($v, $args);
            }
        }
        return $target;
    }

    /**
     * Set multiple resources at once.
     *
     * @param array name => resource
     * @return Xi_Locator
     */
    public function setResources($resources)
    {
        foreach ($resources as $name => $resource) {
            $this->offsetSet($name, $resource);
        }
        return $this;
    }

    /**
     * Alias for offsetSet()
     *
     * @param string resource name
     * @param mixed contents
     * @return Xi_Locator
     */
    public function setResource($name, $resource)
    {
        $this->offsetSet($name, $resource);
        return $this;
    }

    /**
     * @param string resource name
     * @param mixed contents
     * @return Xi_Locator
     */
    public function offsetSet($name, $resource)
    {
        if (is_array($resource)) {
            $resource = $this->createBranch($resource);
        } else {
            $this->_injector->inject($resource);
        }
        parent::offsetSet($name, $resource);
        return $this;
    }

    /**
     * Retrieve a resource.
     *
     * If the resource stored under the given name is an instance of
     * Xi_Factory_Interface, the return value will be retrieved from the
     * factory.
     *
     * If the resource does not exist, return null.
     *
     * @see Xi_Factory_Interface
     * @see offsetGet()
     *
     * @param string resource name
     * @param null|array optional arguments
     * @return null|mixed
     */
    public function getResource($name, $args = null)
    {
        if (!parent::offsetExists($name)) {
            $parent = $this->_parent;
            return isset($parent) ? $parent->getResource($name, $args) : null;
        }

        $value = parent::offsetGet($name);
        if ($value instanceof self) {
            return $value;
        }
        return $this->getFactoryValues($value, $args);
    }

    /**
     * @param string resource name
     * @return null|mixed
     */
    public function offsetGet($name)
    {
        if (!parent::offsetExists($name)) {
            $parent = $this->_parent;
            return isset($parent) ? $parent->offsetGet($name) : null;
        }

        $value = parent::offsetGet($name);
        if ($value instanceof self) {
            return $value;
        }
        return $this->getFactoryValues($value);
    }

    /**
     * @param string resource name
     * @return boolean
     */
    public function offsetExists($name)
    {
        if (!parent::offsetExists($name)) {
            $parent = $this->_parent;
            $value = isset($parent) ? $parent->offsetExists($name) : false;
            return $value;
        }

        return parent::offsetGet($name) !== null;
    }

    /**
     * Alias for offsetExists()
     *
     * @param string resource name
     * @return boolean
     */
    public function hasResource($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Alias for offsetUnset()
     *
     * @param string resource name
     * @return Xi_Locator
     */
    public function removeResource($name)
    {
        $this->offsetUnset($name);
        return $this;
    }

    /**
     * Get all resources stored in the locator. Transforms factories into their
     * values. If you want an unchanged copy, use {@link getArrayCopy()}.
     *
     * @return array
     */
    public function getResources()
    {
        $retval = array();
        foreach ($this as $name => $value) {
            $retval[$name] = $value;
        }
        return $retval;
    }

    /**
     * Get iterator for contents.
     * 
     * @param boolean transform factories
     * @return ArrayIterator
     */
    public function getIterator($transformFactories = true)
    {
        $iterator = new ArrayIterator($this);
        if ($transformFactories) {
            return new Xi_Iterator_Filter($iterator, new Xi_Filter_Callback(array($this, 'getFactoryValues')));
        }
        return $iterator;
    }

    /**
     * Convert locator contents to array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getResources();
    }

    /**
     * Get raw resource value
     *
     * @param string name
     * @return mixed
     */
    public function getRaw($name)
    {
        return parent::offsetGet($name);
    }

    /**
     * Check for existence of raw value
     *
     * @param string name
     * @return boolean
     */
    public function hasRaw($name)
    {
        return parent::offsetExists($name);
    }

    /**
     * Set raw value
     *
     * @param string name
     * @param mixed value
     * @return void
     */
    public function setRaw($name, $value)
    {
        return parent::offsetSet($name, $value);
    }

    /**
     * Alias for {@link offsetGet()}
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offseGet($name);
    }

    /**
     * Alias for {@link offsetSet()}.
     *
     * @param string $name
     * @param mixed $value
     * @return Xi_Locator
     */
    public function __set($name, $value)
    {
        return $this->offsetSet($name, $value);
    }

    /**
     * Alias for {@link offsetExists()}.
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * ArrayObject interface. Not supported by locator.
     *
     * @throws Xi_Locator_Exception
     */
    public function __unset($name)
    {
        throw new Xi_Locator_Exception(__METHOD__.' not supported');
    }

    /**
     * ArrayObject interface. Not supported by locator.
     *
     * @throws Xi_Locator_Exception
     */
    public function offsetUnset($name)
    {
        throw new Xi_Locator_Exception(__METHOD__.' not supported');
    }

    /**
     * ArrayObject interface. Not supported by locator.
     *
     * @throws Xi_Locator_Exception
     */
    public function exchangeArray($array)
    {
        throw new Xi_Locator_Exception(__METHOD__.' not supported');
    }
}
