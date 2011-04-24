<?php
Xi_Loader::loadClass ('Xi_Controller_Model');

/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Model
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Controller_Model_Doctrine_Crud extends Xi_Controller_Model
{
    /**
     * @var array request key => record field name
     */
    protected $_fieldMap = array();
    
    /**
     * @var string record class name
     */
    protected $_recordClass;
    
    /**
     * @param string record class
     * @param array request key => record field name mapping
     */
    public function __construct()
    {
        switch (func_num_args()) {
            case 2:
              $this->setFieldMap(func_get_arg(1));
            // fall through
            case 1:
              $this->setRecordClass(func_get_arg(0));
            break;
            default:
            break;
        }
        $this->setEngine(Zend_Registry::get('doctrine.connection'));
    }
    
    /**
     * @param string class name
     * @return Xi_Controller_Model_Doctrine_Crud
     */
    public function setRecordClass($class)
    {
        $this->_recordClass = $class;
        return $this;
    }
    
    /**
     * @param array request key => record field name mapping
     * @return Xi_Controller_Model_Doctrine_Crud
     */
    public function setFieldMap($fieldMap)
    {
    	$this->_fieldMap = $fieldMap;
    }
    
    /**
     * @return array
     */
    public function getFieldMap()
    {
    	return $this->_fieldMap;
    }
    
    /**
     * @return string
     */
    public function getRecordClass()
    {
    	return $this->_recordClass;
    }
    
    /**
     * @param Xi_Controller_Request
     * @return false|Doctrine_Record
     * @throws Xi_Controller_Model_Exception
     */
    public function create($request)
    {
        if (!isset($this->_recordClass)) {
            throw new Xi_Controller_Model_Exception('Record class name not set');
        }
        $class = $this->_recordClass;
        $record = new $class;
        foreach ($this->_fieldMap as $key => $field)
        {
            if (isset($request->$key)) {
                $record->$field = $request->$key;
            }
        }
        
        if (!$this->validateCreate($record)) {
            return false;
        }
        
        try {
            $record->save();
        } catch (Doctrine_Validator_Exception $e) {
            foreach ($record->getErrorStack() as $field => $error) {
                $this->addError($field, $error);
            }
            return false;
        }
        
        return $record;
    }
}

