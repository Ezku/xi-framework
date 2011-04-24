<?php
/**
 * @category    Xi
 * @package     Xi_Doctrine
 * @subpackage  Xi_Doctrine_Manager
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Doctrine_Manager
{
    /**
     * @var Xi_Doctrine_Manager
     */
    protected static $_instance;
    
    /**
     * @var array<Doctrine_Connection>
     */
    protected $_defaultConnectionStack = array();
    
    /**
     * @return Xi_Doctrine_Manager
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * @param Xi_Doctrine_Manager $instance
     * @return Xi_Doctrine_Manager
     */
    public static function setInstance($instance)
    {
        return self::$_instance = $instance;
    }
    
    /**
     * Retrieves a Doctrine_Connection by name, sets it as current and pushes
     * the old current onto a stack. The old current connection can be restored
     * as current with a call to {@link restoreCurrentConnection()}.
     * 
     * @param string $name
     * @return Doctrine_Connection
     */
    public function getConnectionAsCurrent($name)
    {
        $manager = Doctrine_Manager::getInstance();
        
        $conn = $manager->getConnection($name);
        $this->_defaultConnectionStack[] = $manager->getCurrentConnection();
        $manager->setCurrentConnection($conn->getName());
        return $conn;
    }
    
    /**
     * Restores the latest Doctrine_Connection instance from the connection
     * stack as the current connection and returns it.
     * 
     * @return Doctrine_Connection
     * @throws Xi_Doctrine_Manager_Exception if the stack is empty
     */
    public function restoreCurrentConnection()
    {
        $conn = array_pop($this->_defaultConnectionStack);
        if (empty($conn)) {
            $error = 'Unmatching call to ' . __METHOD__ . ': no connection to restore as current';
            throw new Xi_Doctrine_Manager_Exception($error);
        }
        Doctrine_Manager::getInstance()->setCurrentConnection($conn->getName());
        return $conn;
    }
}
