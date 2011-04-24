<?php
/**
 * Validates the request based on a list of parameters
 * 
 * @category    Xi
 * @package     Xi_Controller
 * @subpackage  Xi_Controller_Plugin
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Controller_Plugin_Validate_Params extends Xi_Controller_Plugin_Validate
{
    public function __construct(Zend_Controller_Plugin_Abstract $plugin, $params = array())
    {
        $validator = new Xi_Validate_Request(null, null, null, $params);
        parent::__construct($plugin, $validator);
    }
}
