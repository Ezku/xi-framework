<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 */
class Xi_Filter_CallbackTest extends PHPUnit_Framework_Testcase
{
    public function testDefersFilteringToCallback()
    {
        $inner = $this->getMock('Zend_Filter_Interface');
        $inner->expects($this->once())->method('filter')->with($this->equalTo('foo'))->will($this->returnValue('bar'));

        $filter = new Xi_Filter_Callback(array($inner, 'filter'));
        $this->assertEquals('bar', $filter->filter('foo'));
    }
}
