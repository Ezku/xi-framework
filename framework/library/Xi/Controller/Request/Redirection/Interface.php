<?php
/**
 * Describes an object capable of changing parameters in a request object to
 * forward execution to a new target
 * 
 * @category    Xi
 * @package     Xi_Controller
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
interface Xi_Controller_Request_Redirection_Interface
{
    /**
     * Set target module name
     *
     * @param string $module
     * @return Xi_Controller_Request_Redirection_Interface
     */
    public function setModule($module);
    
    /**
     * Set target controller name
     *
     * @param string $controller
     * @return Xi_Controller_Request_Redirection_Interface
     */
    public function setController($controller);
    
    /**
     * Set target action name
     *
     * @param string $action
     * @return Xi_Controller_Request_Redirection_Interface
     */
    public function setAction($action);
    
    /**
     * Get target module name
     *
     * @return string
     */
    public function getModule();
    
    /**
     * Get target controller name
     *
     * @return string
     */
    public function getController();
    
    /**
     * Get target action name
     *
     * @return string
     */
    public function getAction();
    
    /**
     * Apply redirection to request
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function apply(Zend_Controller_Request_Abstract $request);
}
