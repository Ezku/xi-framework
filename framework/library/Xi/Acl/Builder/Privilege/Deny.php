<?php
/**
 * @category    Xi
 * @package     Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Acl_Builder_Privilege_Deny extends Xi_Acl_Builder_Privilege_Abstract
{
    /**
     * @var Xi_Acl_Builder_Privilege_Operation_Interface
     */
    protected static $_operation;
    
    /**
     * @return Xi_Acl_Builder_Privilege_Operation_Interface
     */
    public function getOperation()
    {
        if (null === self::$_operation) {
            self::$_operation = new Xi_Acl_Builder_Privilege_Operation_Deny;
        }
        return self::$_operation;
    }
}
