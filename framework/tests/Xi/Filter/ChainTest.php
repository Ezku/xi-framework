<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_ChainTest extends PHPUnit_Framework_Testcase
{
    public function testCanBeConstructedWithFilters()
    {
        $inner = $this->getMock('Zend_Filter_Interface');
        $filter = new Xi_Filter_Chain(array($inner));
        $this->assertEquals(array($inner), $filter->getFilters());
    }

    public function testFiltersCanBeAdded()
    {
        $inner = $this->getMock('Zend_Filter_Interface');
        $filter = new Xi_Filter_Chain;
        $filter->addFilter($inner);
        $this->assertEquals(array($inner), $filter->getFilters());
    }

    public function testCallsAllValidators()
    {
        $one = $this->getMock('Zend_Filter_Interface');
        $two = $this->getMock('Zend_Filter_Interface');

        $one->expects($this->once())->method('filter')->with($this->equalTo('foo'))->will($this->returnValue('bar'));
        $two->expects($this->once())->method('filter')->with($this->equalTo('bar'))->will($this->returnValue('foobar'));

        $filter = new Xi_Filter_Chain(array($one, $two));
        $this->assertEquals('foobar', $filter->filter('foo'));
    }
}
