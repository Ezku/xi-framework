<?php
/**
 * @category    Xi
 * @package     Xi_User
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
abstract class Xi_User_View_Helper_Abstract
{
    /**
     * User object
     *
     * @var Xi_User
     */
    protected $_user;
    
    /**
     * Retrieve User object
     *
     * @return Xi_User
     */
    public function getUser()
    {
        if (null === $this->_user) {
            $this->_user = $this->getDefaultUser();
        }
        return $this->_user;
    }
    
    /**
     * @param Xi_User $user
     * @return Xi_User_View_Helper_IsAllowed
     */
    public function setUser(Xi_User $user)
    {
        $this->_user = $user;
        return $this;
    }
    
    /**
     * Retrieve default user
     *
     * @return Xi_User
     */
    public function getDefaultUser()
    {
        return Zend_Registry::get('user');
    }
}
