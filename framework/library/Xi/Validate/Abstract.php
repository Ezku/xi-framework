<?php
/**
 * Provides a minimal default implementation of Zend_Validate_Interface
 *
 * @category    Xi
 * @package     Xi_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Validate_Abstract implements Zend_Validate_Interface
{
    /**
     * @var array
     */
    protected $_messages = array();

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return array_keys($this->_messages);
    }
}
