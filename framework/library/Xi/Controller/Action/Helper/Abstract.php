<?php
/**
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Action
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
abstract class Xi_Controller_Action_Helper_Abstract extends Zend_Controller_Action_Helper_Abstract
{
    public function getHelper($helper)
    {
        return $this->_actionController->getHelper($helper);
    }
}

