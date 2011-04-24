<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_OuterTest extends PHPUnit_Framework_TestCase
{
    public function testIsConstructedWithInnerFilter()
    {
        $inner = $this->getMock('Zend_Filter_Interface');
        $filter = new Xi_Filter_Outer($inner);
        $this->assertTrue($filter->getFilter() === $inner);
    }

    public function testPassesValueToInnerFilter()
    {
        $inner = $this->getMock('Zend_Filter_Interface');
        $inner->expects($this->once())->method('filter')->with($this->equalTo('foo'));
        $filter = new Xi_Filter_Outer($inner);
        $filter->filter('foo');
    }

    public function testReturnsValueFromInnerFilter()
    {
        $inner = $this->getMock('Zend_Filter_Interface');
        $inner->expects($this->once())->method('filter')->will($this->returnValue('foo'));
        $filter = new Xi_Filter_Outer($inner);
        $this->assertEquals('foo', $filter->filter(null));
    }
}
