<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.xi-framework.com>.
 */

/**
 * @category    Xi
 * @package     Xi_Array
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Array extends ArrayObject
{
    /**
     * @var string which class to instantiate branches as
     */
    protected $_branchClass = __CLASS__;

    /**
     * @param Traversable $data
     * @return void
     */
    public function __construct($data = array())
    {
        parent::__construct(array(), self::ARRAY_AS_PROPS);
        foreach ($data as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * @param Traversable $data
     * @return Xi_Array
     */
    public static function create($data = array())
    {
        return new self($data);
    }

    /**
     * ArrayObject forgets its internal state when serialized: re-establish that
     * state.
     *
     * @return void
     */
    public function __wakeup()
    {
        $this->setFlags(self::ARRAY_AS_PROPS);
    }

    /**
     * @param Traversable $data
     * @return Xi_Array
     */
    public function createBranch($data)
    {
        return Xi_Class::create($this->_branchClass, $this->_getBranchCreationArguments($data));
    }
    
    /**
     * @param Traversable $data
     * @return array
     */
    protected function _getBranchCreationArguments($data)
    {
        return array($data);
    }

    /**
     * ArrayAccess interface
     *
     * @param string|int $name
     * @param mixed $value
     * @return void
     */
    public function offsetSet($name, $value)
    {
        if (is_array($value)) {
            $value = $this->createBranch($value);
        }
        return parent::offsetSet($name, $value);
    }

    /**
     * ArrayAccess interface
     *
     * @param string|int $name
     * @return mixed
     */
    public function offsetGet($name)
    {
        if (!$this->offsetExists($name)) {
            return null;
        }
        return parent::offsetGet($name);
    }

    /**
     * Alias for {@link offsetSet()}.
     *
     * @param string|int $name
     * @param mixed $value
     * @return Xi_Array
     */
    public function set($name, $value)
    {
        $this->offsetSet($name, $value);
        return $this;
    }

    /**
     * Alias for {@link offsetGet()} with an optional default value
     *
     * @param string|int $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (!$this->offsetExists($name)) {
            return $default;
        }
        return parent::offsetGet($name);
    }

    /**
     * Alias for {@link offsetExists()}.
     *
     * @param string|int $name
     * @return boolean
     */
    public function has($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Merge data to array
     *
     * @param Traversable $data
     * @return Xi_Array
     */
    public function merge($data)
    {
        foreach ($data as $key => $value) {
            $this->offsetSet($key, $value);
        }
        return $this;
    }
    
    /**
     * Exchange array contents
     * 
     * @param array $array
     * @return Xi_Array
     */
    public function exchangeArray($array)
    {
        parent::exchangeArray(array());
        $this->merge($array);
        return $this;
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this as $key => $value) {
            if ($value instanceof self) {
                $value = $value->toArray();
            }
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * Select field or fields from array
     *
     * @param string|int|array $key
     * @return Xi_Array
     */
    public function select($key)
    {
        $operation = new Xi_Array_Operation_Select($key);
        return new self($operation->execute($this));
    }

    /**
     * Join array values together with a string
     *
     * @param string $glue
     * @return string
     */
    public function join($glue = ', ')
    {
        return join((array) $this, $glue);
    }

    /**
     * Filter array values with a callback
     *
     * @param callback $callback
     * @return Xi_Array
     */
    public function filter($callback = null)
    {
        $result = isset($callback) ? array_filter((array) $this, $callback) : array_filter((array) $this);
        return new self($result);
    }

    /**
     * Map array values with a callback
     *
     * @param callback $callback
     * @return Xi_Array
     */
    public function map($callback)
    {
        return new self(array_map($callback, (array) $this));
    }
    
    /**
     * Retrieve a flattened version of array contents
     * 
     * @return Xi_Array
     */
    public function flatten()
    {
        $operation = new Xi_Array_Operation_Flatten;
        return new self($operation->execute($this->toArray()));
    }
}
