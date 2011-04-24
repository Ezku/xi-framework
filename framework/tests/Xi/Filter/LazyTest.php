<?php
/**
 * @category    Xi_Test
 * @package     Xi_Filter
 * @group       Xi_Filter
 * @author      Eevert Saukkokoski <eevert.saukkokoski@brainalliance.com>
 * @link        http://www.xi-framework.com
 */
class Xi_Filter_LazyTest extends PHPUnit_Framework_Testcase
{
    public $accessed = 0;

    public function getFilter()
    {
        $this->accessed++;
        $filter = $this->getMock('Zend_Filter_Interface');
        $filter->expects($this->atLeastOnce())
               ->method('filter')
               ->with($this->equalTo('foo'))
               ->will($this->returnValue('bar'));
        return $filter;
    }

    public function tearDown()
    {
        $this->accessed = 0;
    }

    public function testFetchesFilterFromCallback()
    {
        $filter = new Xi_Filter_Lazy(array($this, 'getFilter'));
        $this->assertEquals(0, $this->accessed);
        $this->assertEquals('bar', $filter->filter('foo'));
        $this->assertEquals(1, $this->accessed);
        $this->assertEquals('bar', $filter->filter('foo'));
        $this->assertEquals(1, $this->accessed);
    }
}
