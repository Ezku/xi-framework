<?php
/**
 * @category    Xi
 * @package     Xi_Config
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Config extends Zend_Config
{
    /**
     * @var string
     */
    protected $_branchClass = __CLASS__;

    /**
     * @param array $array
     * @param boolean $allowModifications
     */
    public function __construct($array, $allowModifications = false)
    {
        $this->_allowModifications = (boolean) $allowModifications;
        $this->_loadedSection = null;
        $this->_index = 0;
        $this->_data = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->_data[$key] = $this->createBranch($value);
            } else {
                $this->_data[$key] = $value;
            }
        }
        $this->_count = count($this->_data);
    }

    /**
     * @return boolean
     */
    public function isReadOnly()
    {
        return !$this->_allowModifications;
    }

    /**
     * Create new branch object
     *
     * @param array
     * @return Xi_Config
     */
    public function createBranch($array)
    {
        $class = $this->_branchClass;
        return Xi_Class::create($class, $this->_getBranchCreationArguments($array));
    }

    /**
     * @param array
     * @return array
     */
    protected function _getBranchCreationArguments($array)
    {
        return array($array, $this->_allowModifications);
    }

    /**
     * Only allow setting of a property if $allowModifications
     * was set to true on construction. Otherwise, throw an exception.
     *
     * @param string $name
     * @param mixed $value
     * @throws Xi_Config_Exception
     */
    public function __set($name, $value)
    {
        if ($this->_allowModifications) {
            if (is_array($value)) {
                $this->_data[$name] = $this->createBranch($value);
            } else {
                $this->_data[$name] = $value;
            }
            $this->_count = count($this->_data);
        } else {
            throw new Xi_Config_Exception('Xi_Config is read only');
        }
    }
}

