<?php
/**
 * @category    Xi
 * @package     Xi_User
 * @subpackage  Xi_User_Validate
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_User_Validate_IsAllowed extends Zend_Validate_Abstract
{
    const ACCESS_DENIED = 'accessDenied';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::ACCESS_DENIED => 'Access not allowed'
    );
    
    /**
     * @var Xi_Acl_Action_Interface
     */
    protected $_action;
    
    /**
     * @param Xi_Acl_Action_Interface $action
     */
    public function __construct($action)
    {
        $this->_action = $action;
    }
    
    /**
     * @return Xi_Acl_Action_Interface
     */
    public function getAction()
    {
        return $this->_action;
    }
    
    /**
     * @return Xi_User
     */
    public function getUser()
    {
        return Xi_User::getInstance();
    }
    
    /**
     * Check whether the action retrieved from {@link getAction()} is allowed
     * for the user from {@link getUser()}.
     * 
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if ($this->getUser()->isAllowed($this->getAction())) {
            return true;
        }
        $this->_error(self::ACCESS_DENIED);
        return false;
    }
}
