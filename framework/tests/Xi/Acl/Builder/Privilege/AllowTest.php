<?php
/**
 * @category    Xi_Test
 * @package     Xi_Acl
 * @group       Xi_Acl
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Acl_Builder_Privilege_AllowTest extends PHPUnit_Framework_TestCase
{
    public function testCanAddPrivilege()
    {
        $acl = new Zend_Acl;
        $acl->add(new Zend_Acl_Resource('resource'));
        $acl->addRole(new Zend_Acl_Role('role'));
        
        $builder = new Xi_Acl_Builder_Privilege_Allow($acl);
        $builder->setRole('role');
        $builder->addPrivilege('resource', 'privilege');
        $this->assertTrue($acl->isAllowed('role', 'resource', 'privilege'));
    }
}
