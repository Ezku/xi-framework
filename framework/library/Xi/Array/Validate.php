<?php
/**
 * @category    Xi
 * @package     Xi_Array
 * @subpackage  Xi_Array_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Array_Validate extends Xi_Array implements Xi_Validate_Aggregate
{
    /**
     * @var Zend_Validate_Interface
     */
    protected $_validator;

    /**
     * @var string
     */
    protected $_branchClass = __CLASS__;

    /**
     * @param array $data
     * @param Zend_Validate_Interface $validator
     * @return void
     */
    public function __construct($data = array(), $validator)
    {
        $this->_validator = $validator;
        parent::__construct($data);
    }

    /**
     * @return Zend_Validate_Interface
     */
    public function getValidator()
    {
        return $this->_validator;
    }
    
    /**
     * @param Traversable $data
     * @return array
     */
    protected function _getBranchCreationArguments($data)
    {
        return array($data, $this->getValidator());
    }

    /**
     * Validate values before storing them
     *
     * @param string name
     * @param mixed value
     * @return void
     * @throws Xi_Array_Exception on invalid value
     */
    public function offsetSet($name, $value)
    {
        $validator = $this->getValidator();
        if (!$validator->isValid($value)) {
            throw new Xi_Array_Exception(join((array) $validator->getMessages(), '; '));
        }
        return parent::offsetSet($name, $value);
    }
}
